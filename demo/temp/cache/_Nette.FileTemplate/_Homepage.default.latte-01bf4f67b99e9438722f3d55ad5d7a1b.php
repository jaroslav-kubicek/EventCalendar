<?php //netteCache[01]000386a:2:{s:4:"time";s:21:"0.52886500 1358980533";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:64:"/var/www/EventCalendar/demo/app/templates/Homepage/default.latte";i:2;i:1358980528;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: /var/www/EventCalendar/demo/app/templates/Homepage/default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '2rjlugp9tf')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbd9fcdbf640_content')) { function _lbd9fcdbf640_content($_l, $_args) { extract($_args)
?><div id="tabs">
    <ul>
        <li><a href="#tabs-1">Info</a></li>
        <li><a href="#tabs-2">Demo</a></li>
    </ul>
    <div id="tabs-1">
        <h2>O EventCalendar</h2>
        <p>
            Příští utkání fotbalového či hokejového týmu, plánované koncerty, význámné události -
            přesně pro tyto účely jsem se snažil napsat komponentu, která tyto
            události vypíše v kalendáři a vypořádá se s různými problémy:</p>
        <ul>
            <li>v Anglii začíná týden nedělí, zatímco v Austrálii pondělkem, v obou státech se ale mluví anglicky.</li>
            <li>podpora cizojazyčných (!=EN) názvů měsíců je v PHP chabá (opravte mě, pokud se mýlím)</li>
        </ul>
        <h2>Co to umí</h2>
        <ul>
            <li>stanovit začátek týdne na ne/po</li>
            <li>nastavit jazyk na CZ/EN nebo</li>
            <li>nastavit vlastní názvy měsíců a dnů v týdnu</li>
            <li>nastavit, kde se má zobrazovat navigace pro přepínání měsíce</li>
            <li>nastavit text navigace</li>
            <li>více událostí v jeden den</li>
        </ul>
        <h2>Model událostí</h2>
        <p>Předávaný objekt událostí musí implementovat interface IEventModel - viz kód</p>
    </div>
    <div id="tabs-2">
        <h2>Český kalendář</h2>
        
<?php $_ctrl = $_control->getComponent("czechCalendar"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        <h2>Anglický kalendář (defaultní nastavení)</h2>
<?php $_ctrl = $_control->getComponent("enCalendar"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        <h2>Španělský (využita možnost nastavení vlastních názvů & vypnuta dolní navigace)</h2>
<?php $_ctrl = $_control->getComponent("spainCalendar"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        <h2>Kalendář s efektivním získáváním dat z databáze</h2>
<?php $_ctrl = $_control->getComponent("customCal"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
    </div>
</div>

<?php
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 