<?php
namespace RAAS\CMS\Meta_Checker;
use \RAAS\CMS\Page;
use \RAAS\CMS\Material;

class Module extends \RAAS\Module
{
    protected static $instance;

    public function __get($var)
    {
        switch ($var) {
            case 'cacheFile':
                return $this->parent->cacheDir . '/data.meta_titles.php';
                break;
            default:
                return parent::__get($var);
                break;
        }
    }


    public function deleteCache()
    {
        if (is_file($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }


    public function getMetas(array $IN = array())
    {
        if (is_file($this->cacheFile) && (filemtime($this->cacheFile) > (time() - 3600))) {
            $temp = file_get_contents($this->cacheFile);
            $Set = @unserialize($temp);
        }
        if (!isset($Set) || !$Set) {
            $SQL_query = "SELECT id, 0 AS pid, name, urn, meta_title, meta_keywords, meta_description FROM " . Page::_tablename();
            $Set = $this->SQL->get($SQL_query);
            $SQL_query = "SELECT id, pid, name, urn, meta_title, meta_keywords, meta_description FROM " . Material::_tablename();
            $Set = array_merge($Set, $this->SQL->get($SQL_query));
            $Set = array_map(function($x) {
                foreach(array('meta_title', 'meta_description', 'meta_keywords') as $key) {
                    $x[$key] = \SOME\Text::cuttext($x[$key], 64, '...');
                }
                return $x;
            }, $Set);
            for ($i = 0; $i < count($Set); $i++) {
                for ($j = $i; $j < count($Set); $j++) {
                    if ($i != $j) {
                        foreach (array('name', 'urn', 'meta_title', 'meta_keywords', 'meta_description') as $key) {
                            if (trim($Set[$i][$key]) && (mb_strtolower(trim($Set[$i][$key])) == mb_strtolower(trim($Set[$j][$key])))) {
                                $Set[$i][$key . '_counter']++;
                                $Set[$j][$key . '_counter']++;
                                $Set[$i][$key . '_href'] = (int)$Set[$j]['pid'] . '.' . (int)$Set[$j]['id'];
                                $Set[$j][$key . '_href'] = (int)$Set[$i]['pid'] . '.' . (int)$Set[$i]['id'];
                                $Set[$i]['counter']++;
                                $Set[$j]['counter']++;
                            }
                        }
                    }
                }
            }
            file_put_contents($this->cacheFile, serialize($Set));
        }

        if (isset($IN['sort']) && in_array($IN['sort'], array('pid', 'urn', 'meta_title', 'meta_description', 'meta_keywords'))) {
            $sort = $IN['sort'];
        } else {
            $sort = 'name';
        }
        if (isset($IN['order']) && $IN['order'] == 'desc') {
            $order = -1;
        } else {
            $order = 1;
        }
        $sortF = function($a, $b) use ($sort, $order) {
            if ($a['counter'] != $b['counter']) {
                return $b['counter'] - $a['counter'];
            } else {
                return $order * strcmp($a[$sort], $b[$sort]);
            }
        };
        usort($Set, $sortF);
        $Pages = new \SOME\Pages(isset($IN['page']) ? $IN['page'] : 1, 100);
        $Set = \SOME\SOME::getArraySet($Set, $Pages);
        return array('Set' => $Set, 'Pages' => $Pages, 'sort' => $sort, 'order' => $order);

    }
}