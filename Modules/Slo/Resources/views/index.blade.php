@extends('slo::layouts.master')

@section('content')

<div id="app"></div>

<script>
    let app = new Vue({
        el: '#app',
        router,
        template: '<MainApp/>',
        components: {MainApp},
    });

    /*
    You can use either above or below
    */

    /*let app = new Vue({
        el: "#app",
        router,
        render:h => h(MainApp)
    });*/
</script>
@endsection

