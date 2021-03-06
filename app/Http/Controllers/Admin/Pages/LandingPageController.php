<?php

namespace App\Http\Controllers\Admin\Pages;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use App\Common\CommonResponses;
use App\Model\CMS\LandingPage\LandingPage;
use App\Model\Language;
use App\Model\Section;
use App\Http\Controllers\Pages\PageController;

class LandingPageController extends PageController
{
    public function update(Request $request) {
        $this->validate($request, [
            'language' => 'required',
            'sections' => 'required|array',
            'sections.about' => 'nullable',
            'sections.about.text' => 'required_with:sections.about|string',
            'sections.about.label' => 'required_with:sections.about|string',
            'sections.about.button_label' => 'required_with:sections.about|string',
            'sections.sliders' => 'nullable|array',
            'sections.sliders.*.quote' => 'nullable|string',
            'sections.sliders.*.order' => 'required|int',
            'sections.sliders.*.hidden' => 'required|boolean',
            'sections.sliders.*.path' => 'required|string',
            'sections.stats' => 'nullable|array',
            'sections.stats.*.label' => 'required|string',
            'sections.partners' => 'nullable',
            'sections.partners.label' => 'required_with:sections.partners',
            'sections.partners.text' => 'required_with:sections.partners',
            'sections.partners.button_label' => 'required_with:sections.partners',
        ]);
        
        $language = Language::where('language', $request->language)->first();

        if($language) {
            \DB::beginTransaction();
            try {
                $sections = $request->sections;
                $sections['language'] = $request->language;
                $landingPageDataModel = LandingPage::fromJson($sections);

                $landing_section = Section::where('code','landing')->first();
                $landing_section_by_language = $landing_section->translate($language)->first();
                if($landing_section_by_language) {
                    $landing_section_by_language->update([
                        'data' => $landingPageDataModel->toJson(),
                    ]);
                } else {
                    $landing_section->translations()->create([
                        'language_id' => $language->id,
                        'data' => $landingPageDataModel->toJson(),
                    ]);
                }
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                return CommonResponses::exception($e);
            }
        } else {
            return CommonResponses::error('Invalid language code!', 400);
        }

        return CommonResponses::success('Section updated successfully!', true, $landingPageDataModel->toJson());
    }
}
