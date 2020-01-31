/**
statsh – Processes and System Calls

For this assignment, you are to write a shell named st
atsh that will report process statistics for all of
the child processes that it has launched. The shell will incorporate the appropriate system calls to
implement file I/O redirection,
pipes, and background processes
*/

#include <iostream>
#include <signal.h>
#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <sys/wait.h>
#include <sys/resource.h>
#include <unistd.h>
#include <string.h>
#include <limits.h>
#include <fcntl.h>
#define MAX_ARGS 128
#define CMD_SIZE 2048
#include "main.h"

using namespace std;


/**
 main function, controls loops and calls command processing
 **/
int main (int argc, char * const argv[]) {
    char* line=new char[CMD_SIZE];
    listHead=new node;
    listPtr=listHead;
    
    printf("Shell program, cs385. HW1\nBy Matthew Ramir 667853037 [mramir31]]\n\n");
    
    while(run){ //input of new command
        char buff[PATH_MAX];
        cout<<getenv("USER")<<"@";
        cout<<gethostname(buff, PATH_MAX);
        cout<<buff<<":";
        getcwd(buff, PATH_MAX);  
        cout<<buff<<"$ ";
        cin.getline(line, 2048);
        
        commandNode *command=new commandNode;   
        command=parseCommandPipe(line, command);
        
        
        int stdinfd=-1, stdoutfd=-1;
        for(int i=0;i<4; i++){
            commandNode *parent=NULL, *looper=command, *tmp;
            if(i==3)checkBgProcesses();
            while(looper!=NULL&&looper->set){
                if(i==0)tmp=parseCommandRedirectIn(looper->command, looper);
                else if(i==1)tmp=parseCommandRedirectOut(looper->command, looper);
                else if(i==2)looper->command=trim(looper->command, looper);
                else runCommand(looper, stdinfd, stdoutfd);                
                if(i<2){
                    if(parent==NULL)command=tmp;
                    else parent->next=tmp;                    
                    parent=looper;
                }
                looper=looper->next;
                //break;
            }
        }
        //	printLL(command);
        
    }
}


/**
 parses for | character, if it finds it then it seperate the two commands.
 ie: [ls | grep "a" | grep "b"] becomes [ls]pipe [grep "a"]pipe [grep "b"]
 */

commandNode *parseCommandPipe(char *command, commandNode *nodePtr){
    char *endT, *part=strtok_r(command, "|", &endT);
    commandNode *head=nodePtr, *parent=head;
    int pctr=0;
    while(part!=NULL){
        if(pctr>0){
            commandNode *tmp=new commandNode;
            parent->pipe=true;
            tmp->next=parent->next;
            parent->next=tmp;
            parent=tmp;
            
        }
        parent->command=part;
        parent->set=true;
        part=strtok_r(NULL, "|", &endT);
        pctr++;
    }
    return head;
    
}
/**
 debug function, used to verify parsing commands
 **/
void printLL(commandNode *N){
    commandNode *looper=N;
    printf("\n");
    while(looper!=NULL&&looper->set){
        printf("%s %i %i %i \n", looper->command, looper->stdin, looper->stdout, looper->pipe);
        looper=looper->next;
    }
    printf("\n");
    
}
/**
 parses for < character, if it finds it then it will swap the two commands.
 ie: [echo < out.txt < ha.txt] becomes [ha.txt]stdin [out.txt]stdin [echo]
 */
commandNode *parseCommandRedirectIn(char *command, commandNode *nodePtr){
    char *endT, *part=strtok_r(command, "<", &endT);
    commandNode *head=nodePtr;
    int pctr=0;
    
    
    while(part!=NULL){
        if(pctr>0){
            commandNode *tmp=new commandNode;
            tmp->stdin=true;
            tmp->next=head;
            head=tmp;
            
        }
        
        head->command=part;
        head->set=true;
        part=strtok_r(NULL, "<", &endT);
        pctr++;
    }
    return head;
}
/**
 parses for < character, if it finds it then it will swap the two commands.
 ie: ls>out.txt>lol.txt becomes [lol.txt]stdout [out.txt]stdout [echo]
 */
commandNode *parseCommandRedirectOut(char *command, commandNode *nodePtr){
    char *endT, *part=strtok_r(command, ">", &endT);
    commandNode *head=nodePtr;
    int pctr=0;
    while(part!=NULL){
        if(pctr>0){
            commandNode *tmp=new commandNode;
            tmp->stdout=true;
            tmp->next=head;
            head=tmp;
            
        }
        head->command=part;
        head->set=true;
        part=strtok_r(NULL, ">", &endT);
        pctr++;
    }
    return head;
}

//processes the command cmd using its flags. 
//stdinfd and stdoutfd are any file descriptors sent from previous commands
void runCommand(commandNode *cmd, int &stdinfd, int &stdoutfd){
    
    if(cmd->stdout){
        stdoutfd=fileno(fopen(cmd->command, "w"));
        return;
    }
    if(cmd->stdin){
        stdinfd=fileno(fopen(cmd->command, "r"));
        return;
    }
    
    
    char *args[MAX_ARGS];
    int str=strlen(cmd->command);
    char cmdS[str+1];
    int pipefd[2]={-1,-1};
    
    
    strncpy(cmdS, cmd->command, str);
    strncpy(listPtr->command, cmd->command, str);
    cmdS[str]='\0';
    
    char *tok=strtok(cmdS, " ");
    int i=0;
    for(;i<MAX_ARGS-1&&tok!=NULL;i++){
        if(i==0){
            if(strcmp(tok, "exit")==0)exit(0);
            if(strcmp(tok, "stats")==0){
                printAllUsage();
                return ;
            }
        }
        args[i]=tok;
        tok=strtok(NULL, " ");
    }
    args[i]=NULL;
    
    if(cmd->pipe&&pipe(pipefd)==-1){perror("Pipe failed");return;}
    
    pid_t child=fork();
    
    if(child==-1){perror("Fork failed");close(pipefd[0]);close(pipefd[1]);return;}
    
    if(child==0){//in child
        
        if(cmd->pipe){
            close(pipefd[0]);
            dup2(pipefd[1], 1); //stdout
            close(pipefd[1]);
        }else if(stdoutfd!=-1){
            //	  write(stdoutfd
            dup2(stdoutfd, 1); 
            printf("opening fd:%i\n", stdoutfd);
            // close(stdoutfd);
        }
        if(stdinfd!=-1)dup2(stdinfd, 0); //stdin
        int err= execvp(args[0], args);
        exit(err);
    }
    
    if(stdinfd!=-1){
        close(stdinfd);	
        stdinfd=-1;
    }
    if(stdoutfd!=-1){
        close(stdoutfd);	
        stdoutfd=-1;
    }
    
    if(cmd->pipe){
        close(pipefd[1]);
        stdinfd=pipefd[0];
    }
    
    int w4r=wait4(child, NULL, (cmd->bgprocess?WNOHANG:0), &listPtr->info);
    if(w4r>0)listPtr->done=true;
    
    fixBGProcessing(cmd);
    if(listPtr->done){
        printUsageHeader();
        printUsageFor(listPtr);
    }else listPtr->pid=child;
    listPtr->set=1;
    listPtr->next=new node;
    listPtr=listPtr->next;
}

/**
 checks if any of the background processes are complete, if they are it stores the rusage in an appropriate place
 **/
void checkBgProcesses(){
    node* looper=listHead;
    while(looper!=NULL&&looper->set){
        if(!looper->done){
            int w4r=wait4(looper->pid, NULL, WNOHANG, &looper->info);
            if(w4r>0)looper->done=true;
            if(looper->done){
                printUsageHeader();
                printUsageFor(looper);
            }
        }
        looper=looper->next;
    }
}

/**
 fixes background processing, if parent command pipes to  child and runs background then so should the child
 **/
void fixBGProcessing(commandNode *cmd){
    if(cmd->bgprocess&&cmd->stdin&&cmd->next!=NULL&&cmd->next->set)
        cmd->next->bgprocess=true;
}

/**
prints usage for given command
 **/
void printUsageFor(node* n){
    if(!n->done)return;
    cout<<n->command;
    cout<<"::"<<n->info.ru_stime.tv_sec<<"."<<n->info.ru_stime.tv_usec<<endl;
}

/**
 simple usage header for pretty formatting
 **/
void printUsageHeader(){
    cout<<"\n<command>::System time"<<endl;
}

/**
 stats command, loops through processes and prints usage
 */
void printAllUsage(){
    printUsageHeader();
    node *f=listHead;
    while(f->set){
        printUsageFor(f);
        f=f->next;
    }
}

/**
 trims command and checks for background processing
 r**/
char *trim(char *str, commandNode *c)
{
    char *ret=str;
    while(*ret==' '){
        ret++;
    }
    char* eof=ret+strlen(ret)-1;
    while(*eof==' '){
        *eof='\0';
        eof--;
    }
    if(*eof=='&'){
        c->bgprocess=true;
        *eof='\0';
    }
    return ret;
}
