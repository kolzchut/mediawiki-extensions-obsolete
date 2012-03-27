<?php
/**
* Internationalisation file for extension:Victoria (/skin:Victoria)
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

/**
 * English
 * @author Dror Snir
 * @author Ruth Bridger
 */
 
$messages['en'] = array(
	'victoria-desc' => 'Messages for the Victoria skin',

	'tooltip-p-logo-secondary'        => 'עמוד הבית של {{SITENAME}} בעברית',
	'policy'                          => 'Site policy',
	'policypage'                      => ':he:Project:מדיניות_האתר', //English: 'Project:Site policy',
	'contact'                         => 'Contact us',
	'contactpage'                     => ':he:אודות:צרו_קשר', //English: 'About:Contact us',
	'wr-editor-helppage'              => '{{int:edithelppage}}',
	'wr-helppage'                     => ':he:Help:מדריך למשתמש', //English: 'Help:User guide',
	'wr-help'                         => '{{int:help}}',
	'wr-disclaimer-short'             => 'המידע באתר "כל זכות" הוא מידע כללי ואינו מידע מחייב. הזכויות המחייבות נקבעות על-פי חוק, תקנות ופסיקות בתי המשפט. השימוש במידע המופיע באתר אינו תחליף לקבלת ייעוץ או טיפול משפטי, מקצועי או אחר והסתמכות על האמור בו היא באחריות המשתמש בלבד. יש לעיין בתנאי השימוש.',
	#'wr-additional-disclaimers'      => '', //Shows underneath wr-disclaimer-short; Do not translate unless you need it
	'wr-share-facebook'               => 'Share on Facebook',
	'wr-share-twitter'                => 'Share on Twitter',
	'wr-share-twitter-msg'            => 'See $1 on {{SITENAME}}',
	'wr-share-email'                  => 'Share by email',
	'wr-share-print'                  => 'Printable version',
	'wr-cr-default-name'              => 'Guest',
	'wr-cr-btn-title'                 => 'Sighted any inaccuracies? Have additional information or updates? Click and let us know!',
	'wr-cr-btn-text'                  => 'Change Proposal',
	'wr-cr-btn-alt'                   => '{{int:wr-cr-btn-text}}',
	'wr-slogan'                       => 'All rights. For all of us.',
	'wr-slogan-about'                 => 'non-profit',
	'wr-history'                      => 'Click here for page history.',
	'wr-browser-is-ancient'           => 'You are using an ancient version of Internet Explorer. Unfortunately, our design isn\'t displayed properly in this version; we advise using either Internet Explorer version 8 or later, <a href="http://www.google.com/chrome">Google Chrome</a> or <a href="http://getfirefox.com">Mozilla Firefox</a>.',
	
	'wr-font-resizer-btn-text'        => 'A',
	'wr-font-resizer-reg-tooltip'     => 'Font size: Normal',
	'wr-font-resizer-med-tooltip'     => 'Font size: Medium',
	'wr-font-resizer-big-tooltip'     => 'Font size: Big',
	
	/* Part of "preventing" view source for protected pages */
	'vector-view-viewsource'        => 'Editing disallowed',
	'tooltip-ca-viewsource' 	=> 'This page is protected. You cannot edit it.',
	'accesskey-ca-viewsource' 	=> '', //Disable access key
	
	'skinname-victoria'     => 'Victoria',
);


/**
 * Hebrew (עברית)
 * @author Dror Snir
 * @author Kol-Zchut Staff
 */
 
/* Custom Namespaces */

$messages['he'] = array(
	'victoria-desc' => 'הודעות המיוחדות לעיצוב ויקטוריה',

	'tooltip-p-logo-secondary'        => 'עמוד הבית של {{SITENAME}} בעברית',
	'policy'                          => 'מדיניות האתר',
	'policypage'                      => 'Project:מדיניות האתר',
	'contact'                         => 'צרו קשר',
	'contactpage'                     => 'אודות:צרו קשר',
	'wr-editor-helppage'              => 'עזרה:מדריך לעורך',
	'wr-helppage'                     => 'עזרה:מדריך למשתמש ',
	'wr-help'                         => 'עזרה',

	'wr-disclaimer-short'             => 'המידע באתר "כל-זכות" הוא מידע כללי ואינו מידע מחייב. הזכויות המחייבות נקבעות על-פי חוק, תקנות ופסיקות בתי המשפט. השימוש במידע המופיע באתר אינו תחליף לקבלת ייעוץ או טיפול משפטי, מקצועי או אחר והסתמכות על האמור בו היא באחריות המשתמש בלבד. יש לעיין [[Project:הבהרה משפטית | בתנאי השימוש]].',
 	#'wr-additional-disclaimers'      => '', //Shows underneath wr-disclaimer-short; to be changed in DB per instance

	'wr-cr-default-name'              => 'אורח',
	'wr-cr-btn-title'                 => 'מצאתם אי דיוקים? יש לכם מידע נוסף או עדכונים לערך זה? לחצו וספרו לנו!',
	'wr-cr-btn-text'                  => 'הצעת שינוי',
	'wr-cr-btn-alt'                   => '{{int:wr-cr-btn-text}}',
	'wr-slogan'                       => 'כל הזכויות. לכולנו.',
	'wr-slogan-about'                 => 'ללא כוונת רווח',
	'wr-history'                      => 'לחצו כאן להיסטוריית הדף.',
	'wr-browser-is-ancient'           => 'הנכם משתמשים בדפדפן Internet Explorer בגרסה ישנה. ידוע לנו שהאתר לא מוצג כראוי בגרסה זו; לצפיה תקינה השתמשו ב-Internet Explorer בגרסה 8 ומעלה, <a href="http://www.google.com/chrome">Google Chrome</a> או <a href="http://getfirefox.com">Mozilla Firefox</a>.',

	'wr-font-resize-normal'           => 'א',
	'wr-font-resize-bigger'           => 'א',
	'wr-font-resize-biggest'          => 'א',
	'wr-font-resizer-btn-text'        => 'א',
	'font-resizer-reg-tooltip'        => 'גודל טקסט: רגיל',
	'font-resizer-med-tooltip'        => 'גודל טקסט: בינוני',
	'font-resizer-big-tooltip'        => 'גודל טקסט: גדול',

	/* Part of "preventing" view source for protected pages */
	'vector-view-viewsource'          => 'הדף חסום לעריכה',
	'tooltip-ca-viewsource'           => 'דף זה מוגן. אין באפשרותך לערוך אותו.',
	'accesskey-ca-viewsource' 	  => '', //Disable access key

	'skinname-victoria'               => 'ויקטוריה',
);

/**
/* Arabic (العربية) 
 * @author Jalal Hassan
 * @author Suheir Daksa-Halabi
 * @author Dror Snir
 */

$messages['ar'] = array(
	'victoria-desc' => 'رسائل خاصة بفيكتوريا',

	'tooltip-p-logo-secondary'     => 'עמוד הבית של {{SITENAME}} בעברית',
	'policy'                       => "سياسة الموقع",
	'policypage'                   => ":he:Project:מדיניות_האתר", //Arabic: "{{ns:Project}}:الموقع"
	'contact'                      => "اتصلوا بنا",
	'contactpage'                  => ":he:אודות:צרו_קשר", //Arabic: "حول:اتصلوا بنا"
	'wr-editor-helppage'           => "{{int:edithelppage}}",
	'wr-helppage'                  => ":he:Help:מדריך למשתמש", //Arabic: "مساعدة:دليل للمستخدم"
	'wr-help'                      => "{{int:help}}",
	'wr-disclaimer-short'	       => 'المعلومات الواردة في موقع "كول زخوت" (كل حق) هي معلومات عامة غير ملزمة. الحقوق الملزمة تحدّد حسب القانون، الأنظمة وقرارات الحكم الصادرة عن المحاكم. استخدام المعلومات الواردة في الموقع ليست بديلا للحصول على استشارة أو علاج قانوني، مهني أو آخر وبالتالي فإن الاعتماد على ما ورد فيه هو على مسؤولية المستخدِم فقط. يجب مراجعة [[:he:Project:הבהרה משפטית | شروط الاستخدام]].<br />
המידע באתר "כל זכות" הוא מידע כללי ואינו מידע מחייב. הזכויות המחייבות נקבעות על-פי חוק, תקנות ופסיקות בתי המשפט. השימוש במידע המופיע באתר אינו תחליף לקבלת ייעוץ או טיפול משפטי, מקצועי או אחר והסתמכות על האמור בו היא באחריות המשתמש בלבד. יש לעיין [[:he:Project:הבהרה משפטית | בתנאי השימוש]].',
	'wr-cr-default-name'           => "ضيف",
	'wr-cr-btn-title'              => "هل عثرتم على امور غير دقيقة؟ هل تتوفر لديكم معلومات إضافية أو مستجدات على هذا البند؟ اضغطوا وحدثونا",
	'wr-cr-btn-text'               => "اقتراح تغيير",
	'wr-cr-btn-alt'                => "{{int:wr-cr-btn-text}}",
	'wr-slogan'                    => 'جميع الحقوق. لجميعنا.',
	'wr-slogan-about'              => 'بدون اهداف الربح',
	'wr-history'                   => 'اضغطوا هنا لمراجعة تاريخ الصفحة.',

	'wr-font-resize-normal'        => "أ",
	'wr-font-resize-bigger'        => "أ",
	'wr-font-resize-biggest'       => "أ",
	'wr-font-resizer-btn-text'     => 'أ',
	'font-resizer-reg-tooltip'     => 'حجم النص: عادي',
	'font-resizer-med-tooltip'     => 'حجم النص:متوسط',
	'font-resizer-big-tooltip'     => 'حجم النص: كبير',	

	/* Part of "preventing" view source for protected pages */
	/* missing messages */
	//'vector-view-viewsource'          => 'הדף חסום לעריכה',
	//'tooltip-ca-viewsource'           => 'דף זה מוגן. אין באפשרותך לערוך אותו.',
	//'accesskey-ca-viewsource' 	  => '', //Disable access key
	
	'skinname-victoria'               => 'فيكتوريا',
);
