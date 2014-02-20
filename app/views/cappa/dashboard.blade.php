@extends('layout')

@section('content')
  <h1>Capitalism - A Love Game, it's all about hearts, or is it....</h1>

<br/>
<br/>


<p>Current Hearts: {{ $player->current_hearts}}</p>
<p>Current Dollars: {{ $player->current_dollars}}</p>


<br/>
<br/>

{{ HTML::linkRoute('cappa.addHeart', 'Add Heart') }}

<br/>
<br/>

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
					<td>{{{ $otherPlayer->current_dollars }}}</td>
					<td>{{{ $otherPlayer->share_percentage }}}</td>
					<td>{{ HTML::linkRoute('cappa.giveHeart', 'Give Heart', array('player'=>$otherPlayer->id) ) }}</td>
				</tr>
			@endforeach
	</table>
@else
	<h3>There are no other players in the world, this is sad</h3>
@endif



@stop
