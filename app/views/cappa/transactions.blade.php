@extends('layout')

@section('content')
  <h1>Transactions</h1>

<br/>
<br/>

@if(count($transactions))
	<h3>All transactions</h3>
	<table border=1>
		<tr>
			<th>Giving Player</th>
			<th>Receiving Player</th>
			<th>Hearts Given</th>
			<th>Money Generated</th>
			<th>Money Received</th>
			<th>Player Money Amount</th>
			<th>Player Heart Amount</th>
			<th>Receiving Player Money Amount</th>
			<th>Date</th>
		</tr>
			@foreach($transactions as $trans)
				<tr>
					<td>{{{ $trans->player->username }}}</td>
					<td>{{{ $trans->receiving_player->username }}}</td>
					<td>{{{ $trans->hearts_given }}}</td>
					<td>{{{ $trans->money_generated }}}</td>
					<td>{{{ $trans->money_received }}}</td>
					<td>{{{ $trans->player_money_amount }}}</td>
					<td>{{{ $trans->player_heart_amount }}}</td>
					<td>{{{ $trans->receiving_player_money_amount }}}</td>
					<td>{{{ $trans->created_at }}}</td>
				</tr>
			@endforeach
	</table>
@else
	<h3>There are no transactions in the world, this is sad</h3>
@endif


@stop
