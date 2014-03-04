@extends('layout')

@section('content')
  <h1>Capitalism - A Love Game, it's all about hearts, or is it....</h1>

<br/>
<br/>


<p>Current Hearts: {{ $player->current_hearts}}</p>
<p>Current Dollars: {{ $player->current_money}}</p>


<br/>
<br/>

{{ HTML::linkRoute('cappa.addHeart', 'Add Heart') }}

<br/>
<br/>

{{ Form::open(array('route'=>'cappa.changePoolShare', 'method'=>'POST')) }}
	{{ Form::label('pool_share', 'Pool Share %') }}<br/>
	{{ Form::text('pool_share', Input::old('pool_share', $player->getPoolShare())) }}
	{{ Form::submit('Update Pool Share %') }}
{{ Form::close() }}

<br/>
<br/>
<?php setlocale(LC_MONETARY, 'en_US'); ?>
@if(count($otherPlayers))
	<h3>Other Players In the world</h3>
	<table border=1>
		<tr>
			<th>Username</th>
			<th>Hearts</th>
			<th>Dollars</th>
			<th>Share %</th>
			<th>Action</th>
		</tr>
			@foreach($otherPlayers as $otherPlayer)
				<tr>
					<td>{{{ $otherPlayer->username }}}</td>
					<td>{{{ $otherPlayer->current_hearts }}}</td>
					<td>{{{ money_format('%i', $otherPlayer->current_money) }}}</td>
					<td>{{{ $otherPlayer->pool_factor }}}</td>
					<td>{{ HTML::linkRoute('cappa.giveHeart', 'Give Heart', array('player'=>$otherPlayer->id) ) }}</td>
				</tr>
			@endforeach
	</table>
@else
	<h3>There are no other players in the world, this is sad</h3>
@endif



@stop
