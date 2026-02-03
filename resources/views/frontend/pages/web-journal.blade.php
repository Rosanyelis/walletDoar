@extends('frontend.layouts.master') 
@php
    $defualt = get_default_language_code()??'en'; 
    $en = 'en';
@endphp
@section('content') 
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~--> 
@foreach ($page_section->sections ?? [] as $item)
        
        @if ( $item->section->key == 'about-page-section')
            @include('frontend.sections.about-page-section')
        @elseif($item->section->key == 'announcement-section')
            @include('frontend.sections.announcement-section')
        @elseif($item->section->key == 'app-section')
            @include('frontend.sections.app-section')
        @elseif($item->section->key == 'banner-section')
            @include('frontend.sections.banner-section')
        @elseif($item->section->key == 'brand-section')
            @include('frontend.sections.brand-section')
         @elseif($item->section->key == 'client-feedback-section')
            @include('frontend.sections.client-feedback-section')
         @elseif($item->section->key == 'contact-us-section')
            @include('frontend.sections.contact-us-section')
        @elseif($item->section->key == 'faq-section')
            @include('frontend.sections.faq-section')
        @elseif($item->section->key == 'feature-section')
            @include('frontend.sections.feature-section')
        @elseif($item->section->key == 'security-system-section')
            @include('frontend.sections.security-system-section')
        @elseif($item->section->key == 'services-page-section')
            @include('frontend.sections.services-page-section')
        @elseif($item->section->key == 'why-choose-us-section')
            @include('frontend.sections.why-choose-us-section')
        @endif
        
    @endforeach
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection