<?php
namespace booosta\ui_modal;
\booosta\Framework::init_module('ui_modal');

class UI_Modal extends \booosta\ui\UI
{
  use moduletrait_ui_modal;

  protected $content, $tpl, $vars;
  protected $auto_open = false;
  protected $on_cancellation, $on_confirmation;

  public function after_instanciation()
  {
    parent::after_instanciation();

    if(is_object($this->topobj) && is_a($this->topobj, "\\booosta\\webapp\\Webapp")):
      $this->topobj->moduleinfo['ui_modal'] = true;
      if($this->topobj->moduleinfo['jquery']['use'] == '') $this->topobj->moduleinfo['jquery']['use'] = true;
    endif;
  }

  public function get_htmlonly()
  {
    if($this->tpl):
      $parser = $this->makeInstance('templateparser');
      $content = $parser->parseTemplate($this->tpl, $this->vars);
    else:
      $content = $this->content;
    endif;

    return "<div class='remodal' data-remodal-id='remodal-$this->id' id='remodal-$this->id' 
             data-remodal-options='hashTracking: false, closeOnOutsideClick: false'> $content </div>";
  }

  public function get_js()
  {
    $code = '';

    if($this->auto_open && method_exists($this->parentobj, 'add_jquery_ready')) 
      $this->parentobj->add_jquery_ready("\$('[data-remodal-id=remodal-$this->id]').remodal().open();");

    if(method_exists($this->parentobj, 'add_jquery_ready')) 
      $this->parentobj->add_jquery_ready("\$(document).on('closed', '.remodal', function(e) { $this->on_cancellation }); ");

    if($this->on_confirmation && method_exists($this->parentobj, 'add_jquery_ready'))
      $this->parentobj->add_jquery_ready("\$(document).on('confirmation', '.remodal', function() { $this->on_confirmation; }); ");

    return $code;
  }

  public function get_html_link($text = null, $with_html = true)
  {
    if($text == null) $text = 'Link';
    if($with_html) $html = $this->get_html();
    return "$html <a data-remodal-target='remodal-$this->id'>$text</a>";
  }

  public function get_html_image_link($image, $with_html = true)
  {
    return $this->get_html_link("<img src='$image'>", $with_html);
  }

  public function set_content($content) { $this->content = $content; }
  public function set_auto_open($flag) { $this->auto_open = $flag; }
  public function on_cancellation($content) { $this->on_cancellation = $content; }
  public function on_confirmation($content) { $this->on_confirmation = $content; }

  public function set_template($tpl, $vars = []) 
  { 
    $this->tpl = $tpl;
    $this->vars = $vars;
  }
}
