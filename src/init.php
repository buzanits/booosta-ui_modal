<?php
namespace booosta\ui_modal;

\booosta\Framework::add_module_trait('webapp', 'ui_modal\webapp');

trait webapp
{
  protected function preparse_ui_modal()
  {
    if($this->moduleinfo['ui_modal']):
      $this->add_includes("<script type='text/javascript' src='vendor/booosta/ui_modal/src/remodal.min.js'></script>
                           <link rel='stylesheet' href='vendor/booosta/ui_modal/src/remodal.css'>
                           <link rel='stylesheet' href='vendor/booosta/ui_modal/src/remodal-default-theme.css'>");
    endif;
  }
}
