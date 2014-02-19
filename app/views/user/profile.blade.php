@extends('layout')

@section('content')
  <h2>Welcome "{{ Auth::user()->username }}" to the protected page!</h2>
  <p>Your user ID is: {{ Auth::user()->id }}</p>

<br/>
<br/>
<br/>
Your Data dump:
<br/>
<br/>
<pre>

<?php print_r(Auth::user()->toArray()) ?>

</pre>


@stop
