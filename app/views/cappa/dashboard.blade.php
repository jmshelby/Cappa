@extends('layout')

@section('content')
  <h1>Capitalism</h1>
<h5>A Love Game, it's all about hearts, or is it....</h5>

<p>Current Hearts: {{ $player->current_hearts}} {{ HTML::linkRoute('cappa.addHeart', 'Add Heart') }} </p>

<p>Current Dollars: {{ number_format($player->current_money,2) }}</p>


{{ Form::open(array('route'=>'cappa.changePoolShare', 'method'=>'POST')) }}
	{{ Form::label('pool_share', 'Pool Share %') }}<br/>
	{{ Form::text('pool_share', Input::old('pool_share', $player->getPoolShare())) }}
	{{ Form::submit('Update Pool Share %') }}
{{ Form::close() }}

@if(count($otherPlayers))
	<h3>Other Players In the world</h3>
{{ HTML::linkRoute('cappa.transactionHistory', '(Transaction History)') }}
<br/>
<br/>
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
					<td>{{{ number_format($otherPlayer->current_money, 2) }}}</td>
					<td>{{{ $otherPlayer->share_factor }}}</td>
					<td>{{ HTML::linkRoute('cappa.giveHeart', 'Give Heart', array('player'=>$otherPlayer->id) ) }}</td>
				</tr>
			@endforeach
	</table>
@else
	<h3>There are no other players in the world, this is sad</h3>
@endif



@stop
