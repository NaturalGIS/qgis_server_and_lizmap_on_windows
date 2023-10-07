<?php return array (
  'unknown' => '(1)unknown error',
  'tech' => '(2)There is a technic error on the site. Please try connect to it later.',
  'selector.invalid.syntax' => '(16)Invalid Syntax in the "%1$s" selector (type:%2$s)',
  'selector.invalid.target' => '(17)The selector "%s" doesn\'t correspond to a resource of type: "%s"',
  'selector.module.unknown' => '(18)Unknown module in the selector "%s"',
  'selector.method.invalid' => '(19)Invalid method in the selector "%s"',
  'file.directory.notexists' => '(20)The given directory [%s] does not exists',
  'file.directory.notwritable' => '(21)Unable to write %s, make sure %s is writable',
  'file.write.error' => '(22)Error while writing file %s using temporary %s',
  'file.notexists' => '(23)The file %s doesn\'t exists',
  'inifile.write.error' => '(24)Error while writing ini file %s',
  'file.directory.cannot.remove.fs.root' => '(25)Can\'t delete the filesystem\'s root directory',
  'urls.domain.void' => '(31)Unknow domain for jUrl. It should be indicated into the configuration',
  'cli.unknown.command' => '(40)Unknown command "%s"',
  'cli.param.missing' => '(41)Missing parameter "%s"',
  'cli.option.value.missing' => '(42)value for option "%s" is missing',
  'cli.unknown.option' => '(43)Unknown option "%s"',
  'cli.two.many.parameters' => '(44)Too many parameters',
  'jsession.name.invalid' => '(90)Session name cannot be empty and only accepts alpha-numeric chars.',
  'ad.controller.file.unknown' => '(100)Action %s: controller file %s doesn\'t exists',
  'ad.controller.class.unknown' => '(101)Action %s:  controller class %s doesn\'t exists (file: %s)',
  'ad.controller.method.unknown' => '(102)Action %s: method %s of the controller class %s doesn\'t exists (file: %s)',
  'ad.response.unknown' => '(110)Action %s: response %s doesn\'t exists (file: %s)',
  'ad.response.type.unknown' => '(111)Action %s: the response type "%s" is unknown (file: %s)',
  'ad.response.not.loaded' => '(112)Action %s: the class for the response type "%s" can\'t be loaded (file: %s)',
  'ad.response.type.notallowed' => '(113)Action %s: the response type "%s" is not allowed for the current request (file: %s)',
  'ltx2pdf.exec' => '(120) %s has returned: %s',
  'module.untrusted' => '(130)Module "%s" is unknown or disabled',
  'action.unknown' => '(131)Unknown action "%s"',
  'response.missing' => '(132)response is missing (action %s)',
  'default.response.type.unknown' => '(133)Action %s: unknown default type "%s"',
  'default.response.not.loaded' => '(134)Action %s: the class of default type "%2$s" can\'t be loaded',
  'plugin.unregister' => '(135)The required %1$s plugin for coordinator is not registered',
  'module.unknown' => '(136)Unknown module "%s"',
  'includer.source.missing' => '(140)Includer: the selector "%s" doesn\'t point to a source file',
  'includer.source.compile' => '(141)Includer: the compiler for the selector "%s" has failed',
  'repxml.no.content' => '(150) Undefined content for xml response',
  'repxml.invalid.content' => '(151) Invalid xml content for xml response',
  'repbin.unknown.file' => '(152) Unknown file for binary response (%s)',
  'repredirect.empty.url' => '(153) Impossible to do redirection: empty url',
  'rep.bad.request.method' => '(155) Impossible to use cache on other request type that GET or HEAD',
  'locale.key.selector.invalid' => '(200)The given locale key "%s" is invalid  ( charset %s, lang %s)',
  'locale.key.unknown' => '(201)The given locale key "%s" from module "%s" (and  charset %s, lang %s) does not exists',
  'locale.key.file.unknown' => '(202) The file of the given locale key "%s" from module "%s" (and  charset %s, lang %s) does not exists',
  'tpl.tag.syntax.invalid' => '(300)In the template %2$s, invalid syntax on tag %1$s',
  'tpl.tag.function.invalid' => '(301)In the template %2$s, invalid syntax on the function %1$s',
  'tpl.tag.function.unknown' => '(302)In the template %2$s, unknown fonction %1$s',
  'tpl.tag.modifier.invalid' => '(303)In the tag %s in the template %3$s, invalid syntax on the modifier %2$s',
  'tpl.tag.modifier.unknown' => '(304)In the tag %s  in the template %3$s,  unknown modifier %2$s',
  'tpl.tag.block.end.missing' => '(305)In the template %2$s, end of  bloc %1$s is missing',
  'tpl.tag.block.begin.missing' => '(306)In the template %2$s, begin of bloc %1$s is missing',
  'tpl.tag.phpsyntax.invalid' => '(307)In the tag %s  in the template %3$s, php code %2$s is not allowed',
  'tpl.tag.locale.invalid' => '(308)In the tag %s  in the template %s, locale key is empty',
  'tpl.tag.character.invalid' => '(309)In the tag %s  in the template %3$s, character %2$s not allowed',
  'tpl.tag.bracket.error' => '(310)In the tag %s  in the template %s, bracket error',
  'tpl.not.found' => '(311)The %s template file doesn\'t exist',
  'tpl.tag.meta.unknown' => '(312)In the template %2$s, the meta tag %1$s is unknown',
  'tpl.tag.meta.invalid' => '(313)In the template %2$s, the meta tag syntax %1$s is invalid',
  'tplplugin.block.too.few.arguments' => '(314)In the tag %s  in the template  %s, too few arguments',
  'tplplugin.block.too.many.arguments' => '(315)In the tag %s  in the template  %s, too many arguments',
  'tplplugin.block.bad.argument.number' => '(316)In the tag %s  in the template %3$s, bad argument number (%2$s expected)',
  'tpl.tag.locale.end.missing' => '(317)In the tag %s  in the template  %s, the end of a locale key is missing',
  'tpl.tag.constant.notallowed' => '(318)In the tag %s in the template %3$s, constants (%2$s) are not allowed',
  'tplplugin.cfunction.bad.argument.number' => '(319)In the tag %s in the template %3$s, bad argument number (%2$s expected)',
  'tplplugin.cmodifier.bad.argument.number' => '(320)In the modifier %s in the template %3$s, bad argument number (%2$s expected)',
  'tplplugin.untrusted.not.available' => '(321)In the tag %s in the template %2$s, is not allowed in a untrusted template',
  'tplplugin.function.argument.unknown' => '(322)In the tag %2$s in the template %3$s, the %1$s argument is unknown',
  'tplplugin.function.invalid' => '(323)Invalid syntax in the tag %1$s in the template %3$s',
  'mail.provide_address' => '(350)jMailer: You must provide at least one recipient email address.',
  'mail.mailer_not_supported' => '(351)jMailer: mailer is not supported (%s)',
  'mail.execute' => '(352)jMailer: Could not execute: %s',
  'mail.instantiate' => '(353)jMailer: Could not instantiate mail function..',
  'mail.authenticate' => '(354)jMailer: SMTP Error: Could not authenticate.',
  'mail.from_failed' => '(355)jMailer: The following From address failed: %s',
  'mail.recipients_failed' => '(356)SMTP Error: The following recipients failed: %s',
  'mail.data_not_accepted' => '(357)SMTP Error: Data not accepted.',
  'mail.connect_host' => '(358)SMTP Error: Could not connect to SMTP host.',
  'mail.file_access' => '(359)jMailer: Could not access file: %s',
  'mail.file_open' => '(360)jMailer: File Error: Could not open file:  %s',
  'mail.encoding' => '(361)jMailer: Unknown encoding: %s',
  'mail.signing' => '(362) Signing Error: %s',
  'bindings.nobinding' => '(380) Binding for %s not defined',
  'datetime.invalid' => '(400)jDateTime: invalid date/time (%d-%d-%d %d:%d:%d)',
  'profile.unknown' => '(500)Unknown profile "%s" for "%s"',
  'profile.use.default' => '(501) The given profile "%s" for "%s" doesn\'t exist. The default one is used instead. To not show this error, create the profile or an alias to the default profile.',
  'profile.default.unknown' => '(502)No default profile for "%s"',
  'profile.virtual.no.name' => '(503) The name of a virtuel profile for "%s" is empty',
  'profile.virtual.invalid.params' => '(504) Invalid parameters for the virtual profile "%s" for "%s"',
);
