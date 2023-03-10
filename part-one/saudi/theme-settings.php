<?php
/**
 * @file
 * theme-settings.php
 *
 * Theme settings file for Bootstrap.
 */

function saudi_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {

  if (isset($form_id)) {
    return;
  }

  $form['bootstrap'] = array(
    '#type' => 'vertical_tabs',
    '#prefix' => '<h2><small>' . t('Bootstrap Settings') . '</small></h2>',
    '#weight' => -10,
  );
  // Components.

  $bootswatch_themes = array();
  $default_theme_details = array(
    'name' => t('Default'),
    'description' => t('Pure Bootstrap CSS'),
    'thumbnail' => base_path() . backdrop_get_path('theme', 'saudi') . '/preview.jpg',
  );

  $bootswatch_themes[''] = bootstrap_bootswatch_template($default_theme_details);
  $request = backdrop_http_request('https://bootswatch.com/api/5.json');
  if ($request && $request->code === '200' && !empty($request->data)) {
    if (($api = backdrop_json_decode($request->data)) && is_array($api) && !empty($api['themes'])) {
      foreach ($api['themes'] as $bootswatch_theme) {
        $bootswatch_themes[strtolower($bootswatch_theme['name'])] = bootstrap_bootswatch_template($bootswatch_theme);
      }
    }
  }
  $form['bootswatch'] = array(
    '#type' => 'fieldset',
    '#title' => t('Bootswatch theme'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'bootstrap',
    '#description' => t('Use !bootstrapcdn to serve a Bootswatch Theme. Choose Bootswatch theme here.', array(
      '!bootstrapcdn' => l(t('BootstrapCDN'), 'http://bootstrapcdn.com', array(
        'external' => TRUE,
      )),
    )),
  );


  $form['bootswatch']['saudi_bootswatch'] = array(
    '#type' => 'radios',
    '#default_value' => theme_get_setting('saudi_bootswatch', 'saudi'),
    '#options' => $bootswatch_themes,
    '#empty_option' => t('Disabled'),
    '#empty_value' => NULL,
    '#prefix' => '<div class="section-preview">',
    '#suffix' => '</div>',
  );
  if (empty($bootswatch_themes)) {
    $form['bootswatch']['saudi_bootswatch']['#prefix'] = '<div class="alert alert-danger messages error"><strong>' . t('ERROR') . ':</strong> ' . t('Unable to reach Bootswatch API. Please ensure the server your website is hosted on is able to initiate HTTP requests.') . '</div>';
  }

  $form['navbar'] = array(
    '#type' => 'fieldset',
    '#title' => t('Navbar'),
    '#description' => t('Navigation bar settings.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'bootstrap',
  );
  $form['navbar']['saudi_navbar_position'] = array(
    '#type' => 'select',
    '#title' => t('Navbar Position'),
    '#description' => t('Select your Navbar position.'),
    '#default_value' => theme_get_setting('saudi_navbar_position', 'saudi'),
    '#options' => array(
      'static-top' => t('Static Top'),
      'fixed-top' => t('Fixed Top'),
      'fixed-bottom' => t('Fixed Bottom'),
    ),
    '#empty_option' => t('Normal'),
  );

  $form['navbar']['saudi_navbar_menu_position'] = array(
    '#type' => 'select',
    '#title' => t('Navbar Menu Position'),
    '#description' => t('Select your Navbar Menu position.'),
    '#default_value' => theme_get_setting('saudi_navbar_menu_position', 'saudi'),
    '#options' => array(
      'navbar-left' => t('Left'),
      'navbar-right' => t('Right'),
    ),
    '#empty_option' => t('Normal'),
  );

  $form['navbar']['saudi_navbar_style'] = array(
    '#type' => 'select',
    '#options' => array(
      'bg-primary' => t('Primary'),
      'bg-dark' => t('Dark'),
      'bg-light' => t('Light'),
    ),
    '#title' => t('Navbar background style'),
    '#description' => t('Select the background navbar style.'),
    '#default_value' => theme_get_setting('saudi_navbar_style', 'saudi'),
    '#empty_option' => t('Disabled'),
    '#empty_value' => NULL,
  );

  $form['navbar']['saudi_navbar_user_menu'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add cog with user-menu'),
    '#description' => t('Select if you want cog style right pulled popup menu.'),
    '#default_value' => theme_get_setting('saudi_navbar_user_menu', 'saudi'),
  );

  $form['breadcrumbs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumbs'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'bootstrap',
  );
  $form['breadcrumbs']['saudi_breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show "Home" breadcrumb link'),
    '#default_value' => theme_get_setting('saudi_breadcrumb_home', 'saudi'),
    '#description' => t('If your site has a module dedicated to handling breadcrumbs already, ensure this setting is enabled.'),
  );
  $form['breadcrumbs']['saudi_breadcrumb_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show current page title at end'),
    '#default_value' => theme_get_setting('saudi_breadcrumb_title', 'saudi'),
    '#description' => t('If your site has a module dedicated to handling breadcrumbs already, ensure this setting is disabled.'),
  );

  $form['tweaks'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tweaks'),
    '#group' => 'bootstrap',
  );

  $form['tweaks']['saudi_container'] = array(
    '#type' => 'select',
    '#title' => t('Container type'),
    '#default_value' => theme_get_setting('saudi_container', 'saudi'),
    '#description' => t('Switch between full width (fluid) or fixed (max 1170px) width.'),
    '#options' => array(
      'container' => t('Fixed'),
      'container-fluid' => t('Fluid'),
    )
  );

  $form['tweaks']['saudi_datetime'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show "XX time ago".'),
    '#default_value' => theme_get_setting('saudi_datetime', 'saudi'),
    '#description' => t('If enabled, replace date output for nodes and comments by "XX time ago".'),
  );

  backdrop_add_css(backdrop_get_path('theme', 'saudi') . '/css/settings.css');
  $form['saudi_cdn'] = array(
    '#type' => 'fieldset',
    '#title' => t('BootstrapCDN settings'),
    '#description' => t('Use !bootstrapcdn to serve the Bootstrap framework files. Enabling this setting will prevent this theme from attempting to load any Bootstrap framework files locally. !warning', array(
      '!bootstrapcdn' => l(t('BootstrapCDN'), 'http://bootstrapcdn.com', array(
        'external' => TRUE,
      )),
    '!warning' => '<div class="alert alert-info messages info"><strong>' . t('NOTE') . ':</strong> ' . t('While BootstrapCDN (content distribution network) is the preferred method for providing huge performance gains in load time, this method does depend on using this third party service. BootstrapCDN is under no obligation or commitment to provide guaranteed up-time or service quality for this theme. If you choose to disable this setting, you must provide your own Bootstrap source and/or optional CDN delivery implementation.') . '</div>',
    )),
    '#group' => 'bootstrap',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  // BootstrapCDN.

  $form['saudi_cdn']['saudi_cdn'] = array(
    '#type' => 'select',
    '#title' => t('BootstrapCDN version'),
    '#options' => backdrop_map_assoc(array(
      '5.2.3',
      '5.0.1',
      '3.3.5',
      '3.3.6',
      '3.3.7',
      '3.4.0',
      '3.4.1',
    )),
    '#default_value' => theme_get_setting('saudi_cdn', 'saudi'),
    '#empty_option' => t('Disabled'),
    '#empty_value' => NULL,
  );

  $form['saudi_cdn']['saudi_font_awesome'] = array(
    '#type' => 'select',
    '#title' => t('Font Awesome version'),
    '#options' => backdrop_map_assoc(array(
      '4.4.0',
      '4.7.0',
    )),
    '#default_value' => theme_get_setting('saudi_font_awesome', 'saudi'),
    '#empty_option' => t('Disabled'),
    '#empty_value' => NULL,
  );
}

function bootstrap_bootswatch_template($bootswatch_theme) {
  $output = '<div class="preview">';

  if (isset($bootswatch_theme['thumbnail'])) {
    $output .= '<div class="image">
      <img src="' . $bootswatch_theme['thumbnail']. '" class="img-responsive" alt="' . $bootswatch_theme['name'] . '">
    </div>';
  }
  $output .= '<div class="options">
      <h3>' . $bootswatch_theme['name'] . '</h3>
      <p>' . $bootswatch_theme['description'] . '</p>';
  if (isset($bootswatch_theme['preview'])) {
    $output .= '<div class="btn-group"><a class="btn btn-info" href="' . $bootswatch_theme['preview'] . '" target="_blank">' . t('Preview') . '</a></div>';
  }else{
    $output .= '<div class="btn-group"><a class="btn btn-default disabled" href="#" target="_blank">' . t('No preview') . '</a></div>';
  }
  $output .= '</div>
  </div>';
  return $output;
}
