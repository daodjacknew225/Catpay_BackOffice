@extends('frontend.layouts.master')

@php
    $lang = selectedLang();

    $not_removable_code = $not_removable_code ?? '';
    $system_default = $not_removable_code;

    $banner = App\Models\Admin\SiteSections::getData(Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BANNER_SECTION))->first() ?? null;
    $banner_floting = App\Models\Admin\SiteSections::getData(Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BANNER_FLOTING))->first() ?? null;
    $service = App\Models\Admin\SiteSections::getData(Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::SERVICE_SECTION))->first() ?? null;
    $blog_section = App\Models\Admin\SiteSections::getData(Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BLOG_SECTION))->first() ?? null;

    $page_sections = $page_section->sections ?? [];
@endphp

@section('content')
    @foreach ($page_sections as $item)
        @php $key = $item->section->key ?? ''; @endphp

        @if($key === 'banner-section')
            @include('frontend.partials.banner-section')
        @elseif($key === 'banner-floting')
            @include('frontend.partials.banner-floting')
        @elseif($key === 'work-section')
            @include('frontend.partials.how-work')
        @elseif($key === 'about-section')
            @include('frontend.partials.about')
        @elseif($key === 'security-section')
            @include('frontend.partials.security-section')
        @elseif($key === 'overview-section')
            @include('frontend.partials.map-section')
        @elseif($key === 'why-choose-us-section')
            @include('frontend.partials.choose-section')
        @elseif($key === 'testimonials-section')
            @include('frontend.partials.testimonials')
        @elseif($key === 'brand-section')
            @include('frontend.partials.brand-section')
        @elseif($key === 'faq-section')
            @include('frontend.partials.faq')
        @elseif($key === 'service-section')
            @include('frontend.partials.service')
        @elseif($key === 'contact-section')
            @include('frontend.partials.contact-section')
        @endif
    @endforeach
@endsection

@push('script')
@endpush