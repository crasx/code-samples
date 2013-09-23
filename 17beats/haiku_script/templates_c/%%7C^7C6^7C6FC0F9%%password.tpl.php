<?php /* Smarty version 2.6.25, created on 2010-04-18 20:35:54
         compiled from email/password.tpl */ ?>
Hello <?php echo $this->_tpl_vars['username']; ?>


You are receiving this notification because you have (or someone pretending to be you has) requested a password reset for your account.
If this was not you then you should forward this email to support@17beats.com and we will investigate.

To set a new password visit http://17beats.com/user/passwordreset/<?php echo $this->_tpl_vars['username_safe']; ?>
/<?php echo $this->_tpl_vars['password_auth']; ?>


If you have questions or problems feel free to email support@17beats.com

Peace and Love!
Matt.

<?php echo $this->_tpl_vars['ip']; ?>