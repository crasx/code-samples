<?PHP

class loader{
	
	function loadPoems($start=0){
		$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		global $db;
		return $db->fetch_all_array("SELECT h.id, h.creator uid, h.haiku, h.title, h.likes,
h.approved, cat.name category, count(c.id) comments, h.anonymous, 
CASE h.anonymous
WHEN 1
THEN 'Anonymous'
ELSE u.username
END username
FROM ".T_HAIKU." h left join ".T_CATEGORIES." cat on cat.id= h.category
left join ".T_COMMENTS." c on c.haiku=h.id
left join ".T_USERS." u on u.id=h.creator
where h.approved!=0
group by h.id
order by h.approved desc 
LIMIT $start, $count
");
	}	
	
	function loadCategoryPoems($category, $start=0){
		$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		if(!is_numeric($category))return;
		global $db;
		return $db->fetch_all_array("SELECT h.id, h.creator uid, h.haiku, h.title, h.likes,
h.approved, cat.name category, count(c.id) comments, h.anonymous, 
CASE h.anonymous
WHEN 1
THEN 'Anonymous'
ELSE u.username
END username
FROM ".T_HAIKU." h left join ".T_CATEGORIES." cat on cat.id= h.category
left join ".T_COMMENTS." c on c.haiku=h.id
left join ".T_USERS." u on u.id=h.creator
where h.approved!=0 and h.category=$category
group by h.id
order by h.approved desc 
LIMIT $start, $count
");
	}
	
	function loadRandom(){
		global $db;
		return $db->fetch_all_array("select id from ".T_HAIKU." h where h.approved!=0 order by rand() limit 1");
	}
	function loadPoemCount(){	
		global $db;
		return $db->fetch_all_array("SELECT count(*) count FROM ".T_HAIKU." h where h.approved!=0");
	}
	function loadCategoryPoemCount($category){
		if(!is_numeric($category))return;		
		global $db;
		return $db->fetch_all_array("SELECT count(*) count FROM ".T_HAIKU." h where h.approved!=0 and h.category=$category");
	}
	function loadUserPoemCount($user){
		if(!is_numeric($user))return;		
		global $db;
		return $db->fetch_all_array("SELECT count(*) count FROM ".T_HAIKU." h where h.approved!=0 and h.creator=$user");
	}
		function loadFavoritePoemCount($user){
		if(!is_numeric($user))return;		
		global $db;
		return $db->fetch_all_array("SELECT count(*) count FROM ".T_FAVORITE_HAIKU." h where h.user=$user");
	}
	function loadFavoriteAuthorsPoemCount($user){
		if(!is_numeric($user))return;		
		global $db;
		return $db->fetch_all_array("SELECT count(*) count FROM ".T_HAIKU." h where h.creator in (select f.author from ".T_FAVORITE_AUTHORS." f where f.user=$user) and h.approved!=0 and h.anonymous=0");
	}
	
	function loadUser($username){
		global $db;
		return $db->fetch_all_array("select * from ".T_USERS." where username='".$db->escape(strtolower($username))."' ");
	}
	
	function loadUserPoems($user,  $start=0){
		$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		if(!is_numeric($user))return;
		global $db;
		return $db->fetch_all_array("SELECT h.id, h.creator uid, h.haiku, h.title, h.likes,
h.approved, cat.name category, count(c.id) comments, h.anonymous, 
CASE h.anonymous
WHEN 1
THEN 'Anonymous'
ELSE u.username
END username
FROM ".T_HAIKU." h left join ".T_CATEGORIES." cat on cat.id= h.category
left join ".T_COMMENTS." c on c.haiku=h.id
left join ".T_USERS." u on u.id=h.creator
where h.approved!=0 and h.creator=$user
group by h.id
order by h.approved desc 
LIMIT $start, $count");
	}
	function loadFavoritePoems($user, $start=0){
		$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		if(!is_numeric($user))return;
		global $db;
		return $db->fetch_all_array("SELECT h.id, h.creator uid, h.haiku, h.title, h.likes,
h.approved, cat.name category, count(c.id) comments, h.anonymous, 
CASE h.anonymous
WHEN 1
THEN 'Anonymous'
ELSE u.username
END username
FROM ".T_FAVORITE_HAIKU." f left join ".T_HAIKU." h on f.haiku=h.id 
left join ".T_CATEGORIES." cat on cat.id= h.category
left join ".T_COMMENTS." c on c.haiku=h.id
left join ".T_USERS." u on u.id=h.creator
where h.approved!=0 and f.user=$user
group by h.id
order by h.approved desc 
LIMIT $start, $count");
	}
	
	function loadFavoriteAuthorsPoems($user, $start=0){
		$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		if(!is_numeric($user))return;
		global $db;
		return $db->fetch_all_array("SELECT h.id, h.creator uid, h.haiku, h.title, h.likes,
h.approved, cat.name category, count(c.id) comments, h.anonymous,  u.username
FROM ".T_FAVORITE_AUTHORS." f left join ".T_HAIKU." h on f.author=h.creator
left join ".T_CATEGORIES." cat on cat.id= h.category
left join ".T_COMMENTS." c on c.haiku=h.id
left join ".T_USERS." u on u.id=h.creator
where h.approved!=0 and f.user=$user and h.anonymous=0
group by h.id
order by h.approved desc 
LIMIT $start, $count");
	}	
	function loadFavoriteAuthors($user){

		if(!is_numeric($user))return;
		global $db;
		return $db->fetch_all_array("SELECT u.username author, u.id uid
FROM ".T_FAVORITE_AUTHORS." f left join ".T_USERS." u on u.id=f.author
where f.user=$user");
	}
	
	function loadComments($poem, $count, $start=0){
		if(!is_numeric($poem))return;
		if(!is_numeric($count))$count=DEFAULTCOUNT;
		if(!is_numeric($start))$start=0;
		global $db;
		return $db->fetch_all_array("select c.id, c.text, c.date, u.username, u.id uid ".T_USERS." u, ".T_COMMENTS." c where u.id=c.user and c.haiku=".$poem." and c.flags<".MAXFLAGS);
	}
	
	function loadCategories(){
		global $db;
		return $db->fetch_all_array("select * from ".T_CATEGORIES);
		
	}
	
	function makePageArray($page, $count, $start=1){
		if(!is_numeric($count))$count=DEFAULTCOUNT;
		if(!is_numeric($start)||$start<=0)$start=1;
		$pages=array();
		$s=$start-2;
		if($s<1)$s=1;
		if($s!=1)$pages[]="<a href='$page"."1'>&lt;&lt; First ...</a>";
		$i=0;
		for($i=0;$i<5;$i++){			
			if($s+$i>$count)break;
			if($s+$i==$start)$pages[]=$s;
			else
			$pages[]="<a href='$page$s'>$s</a>";
		}
		if($i+$s<$count)
			$pages[]="... <a href='$page"."$count'>Last &gt;&gt;</a>";
		return $pages;
	}
}

?>