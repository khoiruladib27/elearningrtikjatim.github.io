<?php

if (!function_exists("getApp")) {
    function getApp($conf_char = null)
    {
        $ci = get_instance();
        if ($conf_char) {
            $ci->db->where('conf_char', $conf_char);
            $app = $ci->db
                ->get('system_config')->row_array();
        } else {
            $app = $ci->db
                ->get('system_config')->result_array();
        }
        return $app;
    }
}

if (!function_exists("generatePasswordHash")) {
    function generatePasswordHash($string)
    {
        $string = is_string($string) ? $string : strval($string);
        $pwHash = password_hash($string, PASSWORD_BCRYPT);
        if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
            $pwHash = password_hash($string, PASSWORD_BCRYPT);
        }
        return $pwHash;
    }
}

if (!function_exists("getKategori")) {
    function getKategori()
    {
        $ci = get_instance();
        $kategori = $ci->db
            ->get('kategori')->result_array();
        for ($i = 0; $i < count($kategori); $i++) {
            $kategori[$i]['jml'] = $ci->db->select('count(art_id) as jml')->where('art_ktg_id', $kategori[$i]['ktg_id'])->get('artikel')->row_array()['jml'];
        }
        return $kategori;
    }
}

if (!function_exists("getRecentPost")) {
    function getRecentPost()
    {
        $ci = get_instance();
        $data = $ci->db
            ->limit(5)
            ->order_by('art_hit', 'desc')
            ->get('artikel')->result_array();
        return $data;
    }
}

if (!function_exists("getTags")) {
    function getTags()
    {
        $ci = get_instance();
        $data = $ci->db
            ->get('tags')->result_array();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['jml'] = $ci->db
                ->select('count(artag_id) as jml')
                ->where('artag_tag_id', $data[$i]['tag_id'])
                ->get('artikel_tags')->row_array()['jml'];
        }
        return $data;
    }
}

if (!function_exists("getArtikelTags")) {
    function getArtikelTags($artag_art_id = null)
    {
        $ci = get_instance();
        $data = $ci->db
            ->where('a.artag_art_id', $artag_art_id)
            ->join('tags c', 'a.artag_tag_id=c.tag_id')
            ->join('artikel b', 'a.artag_art_id=b.art_id')
            ->get('artikel_tags a')->result_array();
        return $data;
    }
}
