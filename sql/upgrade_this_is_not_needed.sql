SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


use efull;

alter table jos_users modify column registerDate datetime;
alter table jos_users modify column lastvisitDate datetime;

alter table jos_users drop column usertype;
alter table jos_users drop column sendEmail;


alter table jos_users add column avatar varchar(50) after gid;
alter table jos_users add column gender tinyint(4) not null default 0 after avatar;
alter table jos_users add column birthdate date not null default '0001-01-0' after gender;

update jos_users set avatar=(select avatar from jos_fb_users where userid = id);
update jos_users set gender=(select gender from jos_fb_users where userid = id);
update jos_users set birthdate=(select birthdate from jos_fb_users where userid = id);

alter table jos_fb_users drop column gender;
alter table jos_fb_users drop column avatar;
alter table jos_fb_users drop column birthdate;


alter table jos_fb_users add column love 	int(11)	not null default 0 after userid;
alter table jos_fb_users add column music int(11) not null default 0 after love;
alter table jos_fb_users add column lover_id int(11) after music;

update jos_fb_users set love=(select love from jos_users where userid = id);
update jos_fb_users set music=(select music from jos_users where userid = id);


alter table jos_users drop column love;
alter table jos_users drop column music;


alter table jos_fb_users drop column showOnline;
alter table jos_fb_users drop column websiteurl;
alter table jos_fb_users drop column hideEmail;
alter table jos_fb_users drop column `ordering`;
alter table jos_fb_users drop column `group_id`;
alter table jos_fb_users drop column `view`;
alter table jos_fb_users drop column `rank`;
alter table jos_fb_users drop column `ICQ`;


alter table jos_users add column lover_id 	int(11)	not null default 0 after birthdate;

alter table jos_photo_images add column time int(11) not null default 0;

update jos_users set birthdate='1840-01-01' where birthdate < '0002-01-01' or id=9087;
update jos_users set lastvisitDate = null where lastvisitDate < '0002-01-01';
update jos_users set registerDate = null where registerDate < '0002-01-01';
alter table jos_users modify column birthdate date;
update jos_users set birthdate = null where birthdate < '0002-01-01';

alter table jos_users add column `IP` varchar(20);

alter table jos_fb_users add column whatsup varchar(240);
update jos_fb_users set whatsup = AIM;
alter table jos_fb_users drop column `AIM`;
alter table jos_fb_users drop column `websitename`;

alter table jos_users modify column activation varchar(100);

alter table jos_photo_images add column `private1` int(11) not null default 0;
update jos_photo_images set private1=IFNULL(`private`,0);
alter table jos_photo_images drop column `private`;
alter table jos_photo_images add column `private` int(11) not null default 0 after `comments`;
update jos_photo_images set `private`=`private1`;
alter table jos_photo_images drop column `private1`


CREATE TABLE `jos_lovers` (
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `vote_count` int(11) NOT NULL DEFAULT '0',
  `vote_sum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id1`,`id2`),
  INDEX (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `jos_request` (
  `id` int(11) NOT NULL,
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `desc` varchar(200),
  `type` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
ALTER TABLE `jos_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `jos_blocks` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`who_id`,`from_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;  

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;