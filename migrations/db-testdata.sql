--
-- Main Site
--

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Twitter Meta Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`htmlOptions`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Twitter Meta','config-twitter-meta','system','Twitter meta configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-twitter-meta';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`htmlOptions`,`data`,`order`) VALUES 
	(@form,'active','Active',20,0,'required','{\"title\":\"activate or de-activate.\"}',NULL,0),
	(@form,'page','Page',20,0,'required','{\"title\":\"enable or disabled for all pages.\"}',NULL,0),
	(@form,'post','Post',20,0,'required','{\"title\":\"enable or disabled for all posts.\"}',NULL,0),
	(@form,'card','Card',40,0,'required','{\"title\":\"Card types\",\"items\":[\"summary\",\"summary_large_image\",\"photo\",\"gallery\",\"product\",\"app\",\"player\"]}',NULL,0),
	(@form,'site','Site',0,0,NULL,'{\"title\":\"@username for the website used in the card footer\",\"placeholder\":\"@username\"}',NULL,0),
	(@form,'creator','Creator',0,0,NULL,'{\"title\":\"@username for the content creator / author.\",\"placeholder\":\"@username\"}',NULL,0);

--
-- Facebook Meta Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`htmlOptions`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Facebook Meta','config-facebook-meta','system','Facebook meta configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-facebook-meta';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`htmlOptions`,`data`,`order`) VALUES 
	(@form,'active','Active',20,0,'required','{\"title\":\"activate or de-activate.\"}',NULL,0),
	(@form,'page','Page',20,0,'required','{\"title\":\"enable or disabled for all pages.\"}',NULL,0),
	(@form,'post','Post',20,0,'required','{\"title\":\"enable or disabled for all posts.\"}',NULL,0),
	(@form,'app_id','Application Id',0,0,'required','{\"title\":\"fb app id\",\"placeholder\":\"fb app id\"}',NULL,0),
	(@form,'author','Author',0,0,NULL,'{\"title\":\"author\",\"placeholder\":\"author\"}',NULL,0),
	(@form,'publisher','Publisher',0,0,NULL,'{\"title\":\"publisher\",\"placeholder\":\"publisher\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`type`,`valueType`,`value`) VALUES
	(@site,'site','active','twitter-meta','flag','1'),
	(@site,'site','page','twitter-meta','flag','1'),
	(@site,'site','post','twitter-meta','flag','1'),
	(@site,'site','card','twitter-meta','text','summary_large_image'),
	(@site,'site','site','twitter-meta','text',NULL),
	(@site,'site','creator','twitter-meta','text',NULL),
	(@site,'site','active','facebook-meta','flag','1'),
	(@site,'site','page','facebook-meta','flag','1'),
	(@site,'site','post','facebook-meta','flag','1'),
	(@site,'site','app_id','facebook-meta','text',NULL),
	(@site,'site','author','facebook-meta','text',NULL),
	(@site,'site','publisher','facebook-meta','text',NULL);