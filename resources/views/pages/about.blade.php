@extends('layouts.app')

@section('title', 'Hakkımızda - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <section class="pt-40 pb-10 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 -left-[4%] w-[260px] h-[260px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #83e7ee 0%, #f9eb57 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <span class="badge badge-cyan mb-4">Biz Kimiz ? </span>
            <h1 class="text-heading-3 md:text-heading-2 font-medium">Hakkımızda</h1>
        </div>
    </section>

    <div class="max-w-3xl mx-auto px-5">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 md:p-10 leading-relaxed text-secondary/70 space-y-3">
            {!! nl2br(\App\Models\Setting::get('about_content', '')) !!}
        </div>
    </div>
@endsection


