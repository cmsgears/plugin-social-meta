--
-- Dumping data for table `cmg_core_model_meta`
--

INSERT INTO `cmg_core_model_meta` (`parentId`,`parentType`,`name`,`value`,`type`,`fieldType`,`fieldMeta`) VALUES
	(1,'site','active','1','twitter-meta','checkbox','{\"title\":\"activate or de-activate\"}'),
	(1,'site','page','1','twitter-meta','checkbox','{\"title\":\"enable or disabled for all pages\"}'),
	(1,'site','post','1','twitter-meta','checkbox','{\"title\":\"enable or disable for all posts\"}'),
	(1,'site','card','summary_large_image','twitter-meta','select','{\"options\":[\"summary\",\"summary_large_image\",\"photo\",\"gallery\",\"product\",\"app\",\"player\"]}'),
	(1,'site','site',NULL,'twitter-meta','text','{\"title\":\"@username for the website used in the card footer.\"}'),
	(1,'site','creator',NULL,'twitter-meta','text','{\"title\":\"@username for the content creator / author.\"}'),
	(1,'site','active','1','facebook-meta','checkbox','{\"title\":\"activate or de-activate\"}'),
	(1,'site','page','1','facebook-meta','checkbox','{\"title\":\"enable or disabled for all pages\"}'),
	(1,'site','post','1','facebook-meta','checkbox','{\"title\":\"enable or disable for all posts\"}'),
	(1,'site','appId',NULL,'facebook-meta','checkbox','{\"title\":\"fb app id\"}'),
	(1,'site','author',NULL,'facebook-meta','checkbox','{\"title\":\"author url\"}'),
	(1,'site','publisher',NULL,'facebook-meta','checkbox','{\"title\":\"punlisher url\"}');