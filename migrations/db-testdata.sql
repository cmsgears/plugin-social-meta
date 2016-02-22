--
-- Main Site
--

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Twitter Meta Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Twitter Meta','config-twitter-meta','system','Twitter meta configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-twitter-meta';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`htmlOptions`,`data`) VALUES 
	(@form,'active','Active',40,0,'required',0,'{\"title\":\"activate or de-activate.\"}',NULL),
	(@form,'page','Page',40,0,'required',0,'{\"title\":\"enable or disabled for all pages.\"}',NULL),
	(@form,'post','Post',40,0,'required',0,'{\"title\":\"enable or disabled for all posts.\"}',NULL),
	(@form,'card','Card',80,0,'required',0,'{\"title\":\"Card types\",\"items\":[\"summary\",\"summary_large_image\",\"photo\",\"gallery\",\"product\",\"app\",\"player\"]}',NULL),
	(@form,'site','Site',0,0,NULL,0,'{\"title\":\"@username for the website used in the card footer\",\"placeholder\":\"@username\"}',NULL),
	(@form,'creator','Creator',0,0,NULL,0,'{\"title\":\"@username for the content creator / author.\",\"placeholder\":\"@username\"}',NULL);

--
-- Facebook Meta Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Facebook Meta','config-facebook-meta','system','Facebook meta configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-facebook-meta';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`htmlOptions`,`data`) VALUES 
	(@form,'active','Active',40,0,'required',0,'{\"title\":\"activate or de-activate.\"}',NULL),
	(@form,'page','Page',40,0,'required',0,'{\"title\":\"enable or disabled for all pages.\"}',NULL),
	(@form,'post','Post',40,0,'required',0,'{\"title\":\"enable or disabled for all posts.\"}',NULL),
	(@form,'app_id','Application Id',0,0,'required',0,'{\"title\":\"fb app id\",\"placeholder\":\"fb app id\"}',NULL),
	(@form,'author','Author',0,0,NULL,0,'{\"title\":\"author\",\"placeholder\":\"author\"}',NULL),
	(@form,'publisher','Publisher',0,0,NULL,0,'{\"title\":\"publisher\",\"placeholder\":\"publisher\"}',NULL);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`label`,`type`,`valueType`,`value`) VALUES
	(@site,'site','active','Active','twitter-meta','flag','1'),
	(@site,'site','page','Page','twitter-meta','flag','1'),
	(@site,'site','post','Post','twitter-meta','flag','1'),
	(@site,'site','card','Card','twitter-meta','text','summary_large_image'),
	(@site,'site','site','Site','twitter-meta','text',NULL),
	(@site,'site','creator','Creator','twitter-meta','text',NULL),
	(@site,'site','active','Active','facebook-meta','flag','1'),
	(@site,'site','page','Page','facebook-meta','flag','1'),
	(@site,'site','post','Post','facebook-meta','flag','1'),
	(@site,'site','app_id','App Id','facebook-meta','text',NULL),
	(@site,'site','author','Author','facebook-meta','text',NULL),
	(@site,'site','publisher','Publisher','facebook-meta','text',NULL);