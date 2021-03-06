<?php

namespace App\Model\CMS\LandingPage;

use App\Model\CMS\SerializerInterface;
use App\Model\Setting;
use App\Model\Partner;
use App\Model\CMS\LandingPage\ListOfSlider;
use App\Model\CMS\LandingPage\About;
use App\Common\AppUtils;
use App\Model\CMS\Header\Header;
use App\Model\Section;
use App\Model\Language;
use App\Model\CMS\PageModel;

class LandingPage extends PageModel implements SerializerInterface {
    public $sliders = null;
    public $about = null;
    public $stats = null;
    public $partners = null;
    public $phone = null;
    public $mobile = null;

    public function __construct($data) {
        $this->sliders = ListOfSlider::fromJson($data['sliders'] ?? null);
        $this->about = About::fromJson($data['about'] ?? null);
        $this->stats = ListOfStats::fromJson($data['stats'] ?? null);
        $this->partners = Partners::fromJson($data['partners'] ?? null);

        parent::__construct($data);
    }

    public function toJson() {
        $settings = Setting::first();

        if($settings) {
            $this->phone = $settings->phone;
            $this->mobile = $settings->mobile;
        } 

        return [
            'header' => $this->header ? $this->header->toJson() : null,
            'sliders' => $this->sliders ? $this->sliders->toJson(): null,
            'about' => $this->about ? $this->about->toJson() : null,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'partners' => $this->partners ? $this->partners->toJson() : null,
            'stats' => $this->stats ? $this->stats->toJson() : null,
            'language' => $this->language,
        ];
    }

    public static function fromJson($data) {
        return new LandingPage($data);
    }
}