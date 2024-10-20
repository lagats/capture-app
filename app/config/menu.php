<?php

/* ---------------------------- *
 * Helpers
 * ---------------------------- */

/**
 * Builds a string of HTML attributes from an associative array of key-value pairs.
 *
 * @param array $attrs An associative array of HTML attribute names and their values.
 * @return string A string of HTML attributes in the format "name1='value1' name2='value2' ...".
 */
function buildAttributesString(array $attrs) {
  $attrsString = '';
  if (!empty($attrs)) {
      foreach ($attrs as $key => $value) {
          $attrsString .= "$key='$value' ";
      }
  }
  return $attrsString;
}

/**
 * Returns the icon HTML for the given icon name.
 *
 * @param string $icon The name of the icon to retrieve.
 * @return string The HTML for the icon, or an empty string if no icon is found.
 */
function getIcon(string $icon) {
  return $icon ? Flight::get("icon.$icon") : '';
}



/* ---------------------------- *
 * Render Button/Link
 * ---------------------------- */

/**
 * Renders a menu item based on the provided menu item data.
 *
 * @param array $menuItem The menu item data to render, including attributes, icon, label, and HTML before/after.
 * @return string The rendered HTML for the menu item.
 */
function renderMenuItem(array $menuItem = []) {
  $attrs = $menuItem['attrs'] ?? [];

  $tag = isset($attrs['href']) ? 'a' : 'button';
  $attrsString = buildAttributesString($attrs);

  $output  = $menuItem['htmlBefore'] ?? '';
  $output .= "<{$tag} {$attrsString}>";
  $output .= getIcon($menuItem['icon'] ?? '');
  $output .= isset($menuItem['label']) ? '<span class="label">' . $menuItem['label'] . '</span>' : '';
  $output .= "</{$tag}>";
  $output .= $menuItem['htmlAfter'] ?? '';

  return $output;
}

/**
 * Renders a menu item based on the provided key.
 *
 * @param string $key The key of the menu item to render.
 * @return string The rendered HTML for the menu item.
 */
function renderMenuItemByKey(string $key) {
  $menuItems = Flight::get('app.menuItems') ?? [];
  $menuActions = Flight::get('app.menuActions') ?? [];
  
  $menuItems = array_merge($menuItems, $menuActions);
  $item = $menuItems[$key] ?? [];
  
  if(!(is_array($item) && count($item) > 0)) {
    return '';
  }
  
  return renderMenuItem($item);
}


/* ---------------------------- *
 * Render Menu
 * ---------------------------- */

/**
 * Renders the navigation menu for the application.
 *
 * @param array $options Optional array of options to customize the menu rendering.
 * @return string The rendered HTML for the navigation menu.
 */
function renderNavMenu(array $options = []) {
  $menuItems = Flight::get('app.menuItems') ?? [];
  $menuActions = Flight::get('app.menuActions') ?? [];
  
  // Render
  ob_start();
  ?>
    <div class="nav-menu__container">
        <button class="nav-btn nav-menu__btn" aria-expanded="false">
          <span class="state is-closed">
            <?php echo getIcon('menu'); ?>
            <span class="label">Menu</span>
          </span>
          <span class="state is-open">
            <?php echo getIcon('cancel'); ?>
            <span class="label">Close</span>
          </span>
        </button>
        <nav class="nav-menu">
          <?php 
            foreach ($menuItems as $key => $item) {
              $item['label'] = $item['menuLabel'] ?? $item['label'];
              echo renderMenuItem($item);
            }
          ?>
          <?php
            foreach ($menuActions as $key => $item) {
              $item['label'] = $item['menuLabel'] ?? $item['label'];
              echo renderMenuItem($item);
            }
          ?>
        </nav>
    </div>
  <?php
  $ouput = ob_get_clean();
  
  return $ouput;
}