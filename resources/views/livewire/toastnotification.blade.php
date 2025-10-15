<div>
    @assets
    <link href="{{asset('js/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    @endassets

    @pushonce('scripts')
        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{asset('js/toastr/toastr.min.js')}}"></script>
    @endpushonce

    @script
    <script>
        (function(){
            // normalize and display a toastr notification payload
            function showToastr(e){
                if(!e) return;

                var payload = e;
                // Accept arrays emitted from server-side accidentally
                if (Array.isArray(e)) payload = e[0] || {};

                var type = payload.type || 'success';
                var message = payload.message || payload.msg || '';
                var title = payload.title || '';

                toastr.options = {
                    closeButton: payload.closeButton !== undefined ? payload.closeButton : true,
                    debug: false,
                    newestOnTop: true,
                    progressBar: payload.progressBar !== undefined ? payload.progressBar : true,
                    positionClass: payload.positionClass || 'toast-top-right',
                    preventDuplicates: false,
                    onclick: null,
                    showDuration: payload.showDuration || '300',
                    hideDuration: payload.hideDuration || '1000',
                    timeOut: payload.timeOut || '5000',
                    extendedTimeOut: payload.extendedTimeOut || '1000',
                    showEasing: payload.showEasing || 'swing',
                    hideEasing: payload.hideEasing || 'linear',
                    showMethod: payload.showMethod || 'fadeIn',
                    hideMethod: payload.hideMethod || 'fadeOut'
                };

                if (typeof toastr[type] === 'function') {
                    toastr[type](message, title);
                } else {
                    toastr.success(message, title);
                }
            }

            // function to register Livewire listeners; we'll call it now and also on livewire:load
            function registerLivewireListeners(){
                try {
                    if (window.Livewire && typeof Livewire.on === 'function') {
                        Livewire.on('Notify', function(e){
                            console.debug('Livewire.on Notify', e);
                            showToastr(e);
                        });
                    }
                } catch(err){
                    console.debug('registerLivewireListeners error', err);
                }

                // In-component $wire (works inside Alpine/Livewire contexts)
                try{
                    if (typeof $wire !== 'undefined' && $wire && typeof $wire.on === 'function') {
                        $wire.on('Notify', function(e){
                            console.debug('$wire Notify', e);
                            showToastr(e);
                        });
                    }
                } catch(err){
                    // ignore
                }
            }

            // call once now (if Livewire already loaded)
            registerLivewireListeners();

            // ensure we register once Livewire finishes loading (when Livewire scripts are after this stack)
            window.addEventListener('livewire:load', function(){
                registerLivewireListeners();
            });

            // dispatchBrowserEvent -> window event named 'notify'
            window.addEventListener('notify', function(ev){
                console.debug('Browser event notify', ev.detail);
                showToastr(ev.detail);
            });

            // Server-side session flash (redirect-safe)
            @if(session('notify'))
                showToastr(@json(session('notify')));
            @endif
        })();
    </script>
    @endscript
</div>
