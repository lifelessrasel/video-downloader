@extends('layouts.guest_custom')

@section('content')
<div class="py-16 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-8">DMCA Policy</h1>
        
        <div class="prose prose-invert max-w-none text-gray-300">
            <p>
                <strong>{{ config('app.name') }}</strong> respects the intellectual property rights of others and expects its users to do the same. In accordance with the Digital Millennium Copyright Act of 1998, the text of which may be found on the U.S. Copyright Office website at <a href="http://www.copyright.gov/legislation/dmca.pdf" target="_blank" class="text-blue-400">http://www.copyright.gov/legislation/dmca.pdf</a>, we will respond expeditiously to claims of copyright infringement committed using the {{ config('app.name') }} service.
            </p>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">No Content Hosting</h3>
            <p class="mb-4">
                <strong>Important:</strong> We do NOT host any video, audio, or other content on our servers. All videos download are processed temporarily and deleted immediately. We act as a conduit for users to create private offline copies of content they access.
            </p>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">Submitting a Takedown Notice</h3>
            <p class="mb-4">
                If you are a copyright owner, or are authorized to act on behalf of one, or authorized to act under any exclusive right under copyright, please report alleged copyright infringements taking place on or through the Site by creating a Notice containing the following items:
            </p>
            <ul class="list-disc pl-6 space-y-2 mb-4">
                <li>Identify the copyrighted work that you claim has been infringed.</li>
                <li>Identify the material that you claim is infringing (or to be the subject of infringing activity).</li>
                <li>Provide your mailing address, telephone number, and, if available, email address.</li>
                <li>Include a statement that you have a good faith belief that use of the material in the manner complained of is not authorized by the copyright owner, its agent, or the law.</li>
            </ul>

            <p>
                Deliver this Notice, with all items completed, to our Designated Copyright Agent:
            </p>
            <p class="mt-4 border-l-4 border-blue-500 pl-4 py-2 bg-gray-800">
                Copyright Agent<br>
                {{ config('app.name') }} Inc.<br>
                Email: <a href="mailto:dmca@example.com" class="text-blue-400">dmca@example.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
