
window.imgPrefix = 'https://d3o331zyotmfip.cloudfront.net/img/products/';
//window.token = 'xoxp-2154556232-23767323829-28782303909-2eec218148';

window.user = null;

window.pizzas = [];
window.selected = [];

window.removePizzaButton = '<a class="btn btn-sm remove-pizza" href="javascript:;"><i class="icon glyphicon glyphicon-minus"></i></a>';

$(function(){
	var flavoursSelect = $('#flavours');

	$.getJSON('https://dominos-site.s3.amazonaws.com/products.json', function(data){
		for(i in data){
			if (data[i].category_type == 'PIZZA') {
				pizzas.push(data[i]);
			}
		}

		pizzas = pizzas.sort(function(a, b){
			a = capitalize(a.nickname);
			b = capitalize(b.nickname);
			return a > b ? 1 : (a < b ? -1 : 0)
		});
		fillFlavours();
		flavoursSelect.change();
	});

	$.getJSON('https://slack.com/api/auth.test', {token: token}, function(data) {
		$.getJSON('https://slack.com/api/users.info', {token: token, user: data.user_id}, function(data) {
			user = data.user;
			updateUser();

			sendData = {
				user: user.name,
				action: 'list'
			}
			$.getJSON('/process.php', sendData, function(data){
				for(var i in data){
					selected.push({
						pizza: data[i].flavour,
						pieces: data[i].quantity
					});
				}
				updateSelected();
			});
		});
	});

	$('#selected table').on('click', 'tr td a.remove-pizza', removePizza);

	$('#selected').hide();

	flavoursSelect.change(updateDisplay);

	$('#add-pizza').click(addPizza);

	$('#send-button').click(sendPreferences);

});

function sendPreferences(){
	if (! $('#send-button').attr('disabled')){
		$('#send-button').attr('disabled', 'disabled');
		if (selected.length == 0){
			addPizza();
		}

		sendData = {
			preferences: selected,
			user: user.name,
			action: 'add'
		}

		$.post('/process.php', sendData, function(data){
			alert(data.message);
		}, 'json').fail(function(){
			alert('Infelizmente ocorreu um erro...');
		}).always(function(){
			$('#send-button').removeAttr('disabled');
		});
	}
}

function capitalize(name){
	var s = [];
	var words = name.toLowerCase().split(' ');
	for (var i in words){
		s.push(words[i].charAt(0).toUpperCase() + words[i].slice(1));
	}
	return s.join(' ');
}

function fillFlavours(){
	var flavoursSelect = $('#flavours');
	flavoursSelect.empty();

	for (var i in pizzas){
		var pizza = pizzas[i];
		var opt = $('<option />')
		opt.text(capitalize(pizza.nickname));
		opt.attr('value', pizza.nickname);

		flavoursSelect.append(opt);
	}
}

function updateUser(){
	var img = $('#user-pic');
	img.attr('src', user.profile.image_192);
}

function updateDisplay(){
	var flavour = $('#flavours').val();
	var display = $('#display');
	for (var i in pizzas){
		var pizza = pizzas[i];
		if (pizza.nickname == flavour){
			display.find('img').attr('src', imgPrefix + pizza.ephoto);
			display.find('p').text(pizza.Recipes);
			break;
		}
	}
}

function addPizza(){
	var flavour = $('#flavours').val();
	var pieces = parseInt($('#pieces').val());

	var found = false; 
	for (var i in selected){
		if (selected[i].pizza == flavour){
			selected[i].pieces += pieces;
			found = true;
			break;
		}
	}

	if (! found){
		selected.push({
			pizza: flavour,
			pieces: pieces
		});
	}

	updateSelected();
}

function removePizza(ev){
	var pizza = $(ev.currentTarget).parents('tr').data('pizza');

	for (var i in selected){
		if (selected[i].pizza == pizza){
			selected.splice(i, 1);
			break;
		}
	}
	updateSelected();
}

function updateSelected(){
	$('#selected').find('table tbody').empty();
	for (var i in selected){

		var tr = $('<tr />')

		tr.data('pizza', selected[i].pizza);

		var td = $('<td />');
		td.text(capitalize(selected[i].pizza));
		tr.append(td);

		td = $('<td />');
		td.text(selected[i].pieces);
		tr.append(td);

		td = $('<td />');
		td.append(removePizzaButton);
		tr.append(td);

		$('#selected').find('table tbody').append(tr);

	}
	
	if (selected.length > 0){
		$('#selected').show();
	} else {
		$('#selected').hide();
	}
}