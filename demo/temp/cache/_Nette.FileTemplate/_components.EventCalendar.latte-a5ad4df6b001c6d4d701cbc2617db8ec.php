<?php //netteCache[01]000384a:2:{s:4:"time";s:21:"0.52249100 1358976076";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"/var/www/EventCalendar/demo/app/components/EventCalendar.latte";i:2;i:1345412114;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: /var/www/EventCalendar/demo/app/components/EventCalendar.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8tsylf4e9g')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block _ecCalendar
//
if (!function_exists($_l->blocks['_ecCalendar'][] = '_lbab14cca00a__ecCalendar')) { function _lbab14cca00a__ecCalendar($_l, $_args) { extract($_args); $_control->validateControl('ecCalendar')
?>    <table class="ec-monthTable">
        <caption>
        <?php if ($options["showTopNav"]==TRUE): ?><a class="ajax" href="<?php echo htmlSpecialChars($_control->link("changeMonth!", array('year' => $prev['year'], 'month' => $prev['month']))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($options["topNavPrev"], ENT_NOQUOTES) ?>
</a><?php endif ?>

        <?php echo Nette\Templating\Helpers::escapeHtml($names["monthNames"][$dateInfo["month"]], ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\Helpers::escapeHtml($dateInfo["year"], ENT_NOQUOTES) ?>

        <?php if ($options["showTopNav"]==TRUE): ?><a class="ajax" href="<?php echo htmlSpecialChars($_control->link("changeMonth!", array('year' => $next['year'], 'month' => $next['month']))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($options["topNavNext"], ENT_NOQUOTES) ?>
</a><?php endif ?>

        </caption>
        <tr>
<?php for ($i = 0; $i < 7; $i++): ?>
            <th><?php echo Nette\Templating\Helpers::escapeHtml($names["wdays"][$i], ENT_NOQUOTES) ?></th>  
<?php endfor ?>
        </tr>
<?php for ($i = 0; $i < 6; $i++): ?>
        <tr>
<?php for ($j = 1; $j <= 7; $j++): $dayNum = $j + $i*7 - $dateInfo['firstDayInMonth'] ;if ($dayNum > 0 && $dayNum <= $dateInfo['noOfDays']): if ($events->isForDate($dateInfo["year"],$dateInfo["month"],$dayNum)): ?>
            <td class="ec-validDay ec-eventDay">
                <div class="ec-dayOfEvents"><?php echo Nette\Templating\Helpers::escapeHtml($dayNum, ENT_NOQUOTES) ?></div>
                <div class="ec-eventBox">
<?php $iterations = 0; foreach ($events->getForDate($dateInfo["year"],$dateInfo["month"],$dayNum) as $event): ?>
                    <div class="ec-event"><?php echo $event ?></div>
<?php $iterations++; endforeach ?>
                </div>
            </td>
<?php else: ?>
            <td class="ec-validDay"><?php echo Nette\Templating\Helpers::escapeHtml($dayNum, ENT_NOQUOTES) ?></td>
<?php endif ;else: ?>
            <td class="ec-empty"></td>
<?php endif ;endfor ?>
        </tr>
<?php if (($dayNum >= $dateInfo['noOfDays'] && $i != 6)) break ;endfor ?>
    </table>
<?php if ($options["showBottomNav"]==TRUE): ?>
    <div class="ec-bottomNavigation">
        <a class="ajax" href="<?php echo htmlSpecialChars($_control->link("changeMonth!", array('year' => $prev['year'], 'month' => $prev['month']))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($options["bottomNavPrev"], ENT_NOQUOTES) ?></a>
        <a class="ajax" href="<?php echo htmlSpecialChars($_control->link("changeMonth!", array('year' => $next['year'], 'month' => $next['month']))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($options["bottomNavNext"], ENT_NOQUOTES) ?></a>
    </div>
<?php endif ;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?><div class="eventCalendar"<?php echo ' id="' . $_control->getSnippetId('ecCalendar') . '"' ?>>
<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['_ecCalendar']), $_l, $template->getParameters()) ?>
</div>