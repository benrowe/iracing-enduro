<script src="https://unpkg.com/htmx.org@2.0.4" defer></script>
<script src="https://unpkg.com/htmx-ext-sse@2.2.2" defer></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.body.addEventListener('htmx:configRequest', (event) => {
        event.detail.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
    })
</script>
@if (config('app.debug'))
    {{-- "crash" the entire application by replacing the whole document --}}
    <script>
        document.body.addEventListener("htmx:responseError", function(evt) {
            document.getElementsByTagName('html')[0].innerHTML = evt.detail.xhr.responseText;
        })
    </script>
    <script>
        document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
            ':35729/livereload.js?snipver=1"></' + 'script>')
    </script>
@else
    {{-- user friendly error handling --}}
    <script>
        document.body.addEventListener("htmx:responseError", function(evt) {
            var errdiv = document.getElementById("fatal-error");
            errdiv.innerHTML = `
        <div class="relative w-full rounded-lg border p-4 [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg~*]:pl-7 border-destructive/50 border-red-600 bg-red-50 text-red-600 text-destructive dark:border-destructive [&>svg]:text-destructive mt-4" role="alert" data-id="1">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert h-4 w-4" data-id="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
            <h5 class="mb-1 font-medium leading-none tracking-tight" data-id="3">Error</h5>
            <div class="text-sm [&_p]:leading-relaxed" data-id="4">An error occurred while processing your request. <code>` + evt.detail.xhr.responseText + `</code></div>
            <button onclick="hideError()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10 absolute top-2 right-2" data-id="5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" data-id="6"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg><span class="sr-only" data-id="7">Dismiss</span></button>
        </div>`;
            displayError();
        });

        function displayError() {
            const component = document.getElementById("fatal-error");
            component.classList.remove('hidden');
            setTimeout(() => {
                component.classList.add('opacity-100');
                component.classList.remove('opacity-0');
            }, 10);
        }

        function hideError() {
            const component = document.getElementById("fatal-error");
            component.classList.remove('opacity-100');
            component.classList.add('opacity-0');
            setTimeout(() => {
                component.classList.add('hidden');
            }, 500); // match this with the duration of the transition
        }
    </script>
@endif
