<?php

namespace App\Helpers\Assistant;

class VideoHelpers
{
    public static function getVideoUrl($video)
    {
        $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
        $has_match_youtube = preg_match($yt_rx, $video, $yt_matches);

        $vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
        $has_match_vimeo = preg_match($vm_rx, $video, $vm_matches);

        if ($has_match_youtube) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video, $match);
            $youtube_id = $match[1];
            $videoUrl = 'https://youtube.com/embed/'.$youtube_id;
        } elseif ($has_match_vimeo) {
            preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\#?)(?:[?]?.*)$%im', $video, $regs);

            $vimeo_id = $regs[3];
            $videoUrl = 'https://player.vimeo.com/video/'.$vimeo_id;
        } else {
            $videoUrl = $video;
        }

        return $videoUrl;
    }
}
