<?php

/**
 * Plugin Comentario.
 */

use Shaarli\Config\ConfigManager;
use Shaarli\Plugin\PluginManager;
use Shaarli\Render\TemplatePage;

/**
 * Display an error everywhere if the plugin is enabled without configuration.
 *
 * @param $conf ConfigManager instance
 *
 * @return mixed - linklist data with Comentario plugin.
 */
function comentario_init($conf)
{
    $comentarioUrl = $conf->get('plugins.COMENTARIO_SERVER');
    if (empty($comentarioUrl)) {
        $error = t('Comentario plugin error: ' .
            'Please define the "COMENTARIO_SERVER" setting in the plugin administration page.');
        return [$error];
    }
}

/**
 * Render linklist hook.
 * Will only display Comentario comments on permalinks.
 *
 * @param $data array         List of links
 * @param $conf ConfigManager instance
 *
 * @return mixed - linklist data with Comentario plugin.
 */
function hook_comentario_render_linklist($data, $conf)
{
    $comentarioUrl = $conf->get('plugins.COMENTARIO_SERVER');
    if (empty($comentarioUrl)) {
        return $data;
    }

    // Only display comments for permalinks.
    if (count($data['links']) == 1 && empty($data['search_tags']) && empty($data['search_term'])) {
        $link = reset($data['links']);
        $comentarioHtml = file_get_contents(PluginManager::$PLUGINS_PATH . '/comentario/comentario.html');

        $comentario = sprintf($comentarioHtml, $comentarioUrl, $comentarioUrl, $link['id'], $link['id']);
        $data['plugin_end_zone'][] = $comentario;
    } else {
        $button = '<span><a href="' . ($data['_BASE_PATH_'] ?? '') . '/shaare/%s#comentario-thread">';
        // For the default theme we use a FontAwesome icon which is better than an image
        if ($conf->get('resource.theme') === 'default') {
            $button .= '<i class="linklist-plugin-icon fa fa-comment"></i>';
        } else {
            $button .= '<img class="linklist-plugin-icon" src="' . $data['_ROOT_PATH_'] . '/plugins/comentario/comment.png" ';
            $button .= 'title="Comment on this shaare" alt="Comments" />';
        }
        $button .= '</a></span>';
        foreach ($data['links'] as &$value) {
            $commentLink = sprintf($button, $value['shorturl']);
            $value['link_plugin'][] = $commentLink;
        }
    }

    return $data;
}

/**
 * When linklist is displayed, include comentario CSS file.
 *
 * @param array $data - header data.
 *
 * @return mixed - header data with comentario CSS file added.
 */
function hook_comentario_render_includes($data)
{
    if ($data['_PAGE_'] == TemplatePage::LINKLIST) {
        $data['css_files'][] = PluginManager::$PLUGINS_PATH . '/comentario/comentario.css';
    }

    return $data;
}

/**
 * This function is never called, but contains translation calls for GNU gettext extraction.
 */
function comentario_dummy_translation()
{
    // meta
    t('Let visitor comment your shaares on permalinks with Comentario.');
    t('Comentario server URL (without \'http://\')');
}
