<script type="text/javascript">

var texte = '{"data":[{"id":"1382732622036868","name":"Will Amikdcjcfif Sidhuson","first_name":"Will","last_name":"Sidhuson","gender":"male","locale":"fr_FR","picture":{"data":{"is_silhouette":true,"url":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/s200x200\/10354686_10150004552801856_220367501106153455_n.jpg?oh=60ab9564f7390a6d717cd2d8f82d8d40&oe=555CE450&__gda__=1431912006_de209242f69e1b7e4eb8974ea7ad1385"}}},{"id":"1383088988670250","name":"Lisa Amijgdhfcggj Smithwitz","first_name":"Lisa","last_name":"Smithwitz","gender":"female","locale":"fr_FR","picture":{"data":{"is_silhouette":true,"url":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfa1\/v\/t1.0-1\/s200x200\/1379841_10150004552801901_469209496895221757_n.jpg?oh=68f1d81e5adea117350ee9ec0c0242c0&oe=556431F8&__gda__=1431462769_5ed6cbc67da2fa89862ccf10b4dfd21a"}}},{"id":"1379285485717850","name":"Mike Amijibcdijae Goldmansen","first_name":"Mike","last_name":"Goldmansen","gender":"male","locale":"fr_FR","picture":{"data":{"is_silhouette":true,"url":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/s200x200\/10354686_10150004552801856_220367501106153455_n.jpg?oh=60ab9564f7390a6d717cd2d8f82d8d40&oe=555CE450&__gda__=1431912006_de209242f69e1b7e4eb8974ea7ad1385"}}}],"paging":{"next":"https:\/\/graph.facebook.com\/v2.2\/1375882546059268\/friends?fields=id,name,first_name,last_name,gender,locale,birthday,location,hometown,relationship_status,picture.type%28large%29&limit=100&access_token=CAAT08Fex668BALyED6KyiqiZCfSiEwKSZBur6HG3G0YZBZCScooesBHMmN6ZBKE5RZC5TOVee9u2ToANpHB9sjJEZCxKO7kOsV6PxFN9ZBD0cySqFaZA3hfTaQCbZAIkJ69ZAYOGxRC7uv35oEAvtji9grF2dSZAR5eauTTqyxknWdgsDGld2P28zTenTZAZAtKDmt0S5ygv4MHZBMlWVcgyNMpKbgO&offset=100&__after_id=enc_AezYVtNi_0_FDU_EmCy4w-o2tlRy2LVtQ9xZxZ5hsc7rhWEdj0yDhsqPT-lXjNi97DxFkSMwHNcm2HTPk5yoIsO6"},"summary":{"total_count":3}}';

var matrice = '[[0,0,1],[0,0,1],[1,1,0]]';

var friendList = JSON.parse(text);
var matrix = JSON.parse(matrice);

var nodes = new Object();
nodes[0]["name"] = "nom";

var links = new Object();
links[0]["source"] = 0;
links[0]["target"] = 0;

var json = new Object();
json[0]["nodes"] = nodes;
json[1]["links"] = links;

var nbAmi = 0;


nbAmi = friendList.summary.total_count;

for (var i=0;i<=nbAmi;i=i+1){
	node.date[i]name = friendList.data[i].name;

}

</script>