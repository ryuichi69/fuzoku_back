	var map = new google.maps.Map(document.getElementById("map"), {
		zoom: 7,
		center: new google.maps.LatLng(36,138),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
        
	function getLatLng(place1,place2) {
		var geocoder = new google.maps.Geocoder();
		var start;
		var goal;
                var distance;
                //start地点の登録
		geocoder.geocode({
			address: place1,
			region: 'jp'
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				for (var r in results) {
					if (results[r].geometry) {
						start = results[0].geometry.location;//発地の住所を取得
					}
				}
			}
		});
                
                //goal地点の登録
		geocoder.geocode({
			address: place2,
			region: 'jp'
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				for (var r in results) {
					if (results[r].geometry) {
						goal = results[0].geometry.location;//発地の住所を取得
					}
				}
			}
		});
                //ここから二点間の距離を計算
                distance = google.maps.geometry.spherical.computeDistanceBetween(place1,place2);
                //debugの為アラート
                
                alert(distance);
	}

