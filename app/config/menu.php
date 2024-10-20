<?php

/* ---------------------------- *
 * Helpers
 * ---------------------------- */

function buildAttributesString(array $attrs) {
  $attrsString = '';
  if (!empty($attrs)) {
      foreach ($attrs as $key => $value) {
          $attrsString .= "$key='$value' ";
      }
  }
  return $attrsString;
}

function getIcon(string $icon) {
  return $icon ? Flight::get("icon.$icon") : '';
}



/* ---------------------------- *
 * Render Button/Link
 * ---------------------------- */

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