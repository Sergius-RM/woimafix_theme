<?php

// Добавление новой роли "Provider"
function add_provider_role() {
  add_role('provider', 'Provider', array(
    'read' => true,
    'upload_files' => true,
    'edit_pages' => true,
    'edit_published_pages' => true,
    'publish_pages' => true,
    'edit_provider' => true,
    'read_provider' => true,
    'delete_provider' => true,
    'edit_providers' => true,
    'publish_providers' => true,
    'delete_providers' => true,
  ));
}
add_action('init', 'add_provider_role');

// Удаление лишних прав
// Ограничение видимости в админке
function restrict_provider_admin_access() {
  if (current_user_can('provider')) {
      global $menu, $submenu;

      // Удаление меню "Посты"
      remove_menu_page('edit.php');

      // Удаление меню "Страницы"
      remove_menu_page('edit.php?post_type=page');

      // Удаление меню "Комментарии"
      remove_menu_page('edit-comments.php');

      // Удаление меню "Внешний вид"
      remove_menu_page('themes.php');

      // Удаление меню "Плагины"
      remove_menu_page('plugins.php');

      // Удаление меню "Пользователи"
      remove_menu_page('users.php');

      // Удаление меню "Инструменты"
      remove_menu_page('tools.php');

      // Удаление меню "Настройки"
      remove_menu_page('options-general.php');

      // Удаление подменю "Профиль"
      remove_menu_page('profile.php');

      // Удаление меню "Services"
      remove_menu_page('edit.php?post_type=services');

      // Удаление подменю "Все Services" под "Services"
      unset($submenu['edit.php?post_type=services'][5]);

      // Удаление подменю "Добавить новый" под "Services"
      unset($submenu['edit.php?post_type=services'][10]);

      // Удаление подменю "Добавить новый" под "Services"
      unset($submenu['edit.php?post_type=provider'][15]);
      unset($submenu['edit.php?post_type=provider'][16]);
      // Удаление подменю "Библиотека" под "Медиа"
      unset($submenu['upload.php'][10]);

      unset($menu[2]);  // dashboard
      unset($menu[5]);  // posts
      unset($menu[15]); // links
      unset($menu[20]); // media
      unset($menu[70]); // service
      unset($menu[59]); // profile
  }
}
add_action('admin_menu', 'restrict_provider_admin_access');


function remove_links_for_provider($wp_admin_bar) {

  if (!current_user_can('provider')) {
      return;
  }

  $wp_admin_bar->remove_node('new-page');
  $wp_admin_bar->remove_node('new-media');
  $wp_admin_bar->remove_node('new-services');

}
add_action('admin_bar_menu', 'remove_links_for_provider', 999);

?>