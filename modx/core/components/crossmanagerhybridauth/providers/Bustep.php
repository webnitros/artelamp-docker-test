<?php

namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\Exception;
use Hybridauth\Data\Collection;
use Hybridauth\User\Profile;


class Bustep extends OAuth2
{

    //data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAYAAABXuSs3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQ1IDc5LjE2MzQ5OSwgMjAxOC8wOC8xMy0xNjo0MDoyMiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTkgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk0RDE4ODJBMkJDNjExRUI4OUM2Q0VGM0E4Q0E5OTEyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk0RDE4ODJCMkJDNjExRUI4OUM2Q0VGM0E4Q0E5OTEyIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTREMTg4MjgyQkM2MTFFQjg5QzZDRUYzQThDQTk5MTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTREMTg4MjkyQkM2MTFFQjg5QzZDRUYzQThDQTk5MTIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7WyweGAAAE3ElEQVR42uxZXWxTZRh+TtvZYrF0Ose60jrKVsYUwyaEGyIuXHhnbOKcmHAhV5polF1MIV4gF4Rg/IkJidxo/IG4wByBhEQTknlpxDGIbGHttm5dbd2Pq465rn/H7zvn9LQ9Pee0ZWfbWcKbvHn79uec53vO837f2+9jWJbFRjQDNqiZsi96fujZEIA7X+ssm/FHiL9K/CLxceLLxFmNfYH4sHCPDuGe5TGuYD7iZ+9NTDX6p6KYnJnDQnwZ6XSajJhBhtyTYRjQOpGPIBEyeWGsMpo22zZbmuvs9uZml+Ow1+0MkPu+T/zHSoEbiZ/2h/7sHvCPYzQywxHDg6WWi3xtK0Uo5HxkGZ7v5XQKs7H7mPnnPv4ITmGHY6Kxramht8lVf5Z84wTxdLnAT98aGeu+cesuUqk0d3XKWIZli5iWZ16JaZaAZcAIOQTwfM4/ApqPRqYRnp1H+1K8u9XrgcB+yVnllZHJMAc6QUBnBKazzJXK5ZhmmVzOFOTIy9mCPJ5M4sbgECgWiqkUcFoUn1F5ZJk2iOCQY14m5xgsijyzDCvNUZSzYp4dHINEMoWBwAR9+3NpwUqBdwwFQ9uC0VmRyQxX9HJMa6NxNk8urCgnfrA0jhHZ3A2GnMLMpgj85UAoKmo2C1aeaQhPRJlpuZyVYTqrcaXcH4pw2NSA75ua+1tgli1L06ul8RzzQHh+nn60Vw143X9LywVMr6fGs3Fxka55cKhNh+ZEJp0HtljjGVb7eTxf45BonMYkh0G9OAvmZb1onGHL6A5zmtWPxrODVV05pTKRRrkVlOZ3fCcxf7QZdpkVLbUYw/RvfTj2yc+4J8gjv1eRX0EZQS58LAlcDmw5Ghft32lMxFK56xmteLzOjvoX3sD31gT2nuqvWOMVMa7ErFKvItrkT3jp+PWCXuX2/vcw+sF+eHYeQBfTj09L9Cpy+appXGr5Gt/96zjG4nzPadZI4wY1jVcyj0tgi7PJmHM3fF0HccBKksAAzjzAPC7HuPYaf+YIbl87UvwEhntw4sPr4hD1p3GhOAVMRB5W2Ou2YMuuTpz6ohqWd79GrwYaV2acVWJaPpcWZ74NO5/HNyffwuHt7Xjz6Je4/JUZkGgcEo1jvTVO467wL3j9ZohkVaht9OlU45BfQaVjW6nGNetVpFbYqzyHi/tc3PvRYJ8mvYr2Gne/iKvnDuVdsQrVdU/CRnu72CCunjeLTK9E45r1KqLZavGUTXLRxCL+GujHt+e/w4W8f/m66FWe7fsIB/tK7T8x+tP4w358rXuV1fzPWQ7jCyaTUVXja72vQqPRyMFMqAGP2B/dpDuNP2Yxc9jUgN+psdt0p/H6J6rpRzfVgF/xuhy603jTNm5L5Yoa8EstDa6pHVtrdaNxT20Nnt7uClNsasBpAXTt8TbAUlW17ho3G01oI1iIHROOcFT3xy/tdDs/bt/TQn5sWDeNUzvU2gKKRcq22onE8VavZ5PVYn570B9EIDpdsldROwNCUa7eq3i21qCtyQOv23GOYqnkDIju6r/jdTv7iZ8ZCoYaR0IRROdiiC3FyQ0zmu4dGsg8bbNYyOxhB50cSJ0FBMCXH/TUrZf4NXKhDuI+rrEGnFyvqq0lidMC/J14nyCNxEqOC7MFe0Fw3Rjz8Cx/je1/AQYAOYx34PtjkTUAAAAASUVORK5CYII=

    protected $apiBaseUrl = 'http://oauth2.bustep.ru/oauth2';
    protected $authorizeUrl = 'http://oauth2.bustep.ru/oauth2/auth';
    protected $accessTokenUrl = 'http://oauth2.bustep.ru/oauth2/token';

    /**
     * @return bool|Profile
     * @throws Exception
     * @throws \Hybridauth\Exception\HttpClientFailureException
     * @throws \Hybridauth\Exception\HttpRequestFailedException
     * @throws \Hybridauth\Exception\InvalidAccessTokenException
     */
    function getUserProfile()
    {
        $data = new Collection($this->apiRequest('profile', 'POST'));

        if (!$data->exists('identifier')) {
            throw new Exception('User profile request failed! Fandeco returned an invalid response.');
        }

        $userProfile = new Profile();
        $userProfile->identifier = $data->get('identifier');
        $userProfile->email = $data->get('email');
        $userProfile->displayName = $data->get('displayName');
        $userProfile->photoURL = $data->get('photoURL');
        $userProfile->webSiteURL = $data->get('webSiteURL');
        $userProfile->profileURL = $data->get('profileUrl');
        $userProfile->phone = $data->get('phone');
        $userProfile->address = $data->get('address');
        $userProfile->region = $data->get('region');
        $userProfile->city = $data->get('city');
        $userProfile->zip = $data->get('zip');

        return $userProfile;
    }

}

