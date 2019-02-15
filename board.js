$(function() {

    var vm = new Vue({
        el: "#board",
        data: {
            name: "",
            comment: "",
            resp: "",
        },
        methods: {
            postComment: function(){
                var self = this;
                // input内、空白の際のエラーメッセージ表示
                if(self.name == "" && self.comment != ""){
                    alert("Input your name");
                }else if(self.name != "" && self.comment == ""){
                    alert("Input a comment");
                }else if(self.name == "" && self.comment == ""){
                    alert("Input your name\nInput a comment");
                }
                $.ajax({
                    url: "api.php",
                    type: "post",
                    dataType: "json", // 「json」を指定するとresponseがJSONとしてパースされたオブジェクトになる
                    data: { // 送信データを指定
                        name: self.name,
                        comment: self.comment
                    }
                // 鬼木さん追加
                }).done(function(response){
                    self.comment = "";
                    self.getCommentList();
                }).fail(function(response){
                });
            },
            getCommentList: function(){
                var self = this;
                $.ajax({
                    url: "api.php",
                    type: "get",
                    dataType: "json",
                    }).done(function(response){
                        self.resp = response[0];
                        $("#msg1").text(response[1]);
                        $("#msg2").text(response[2]);
                    }).fail(function(){
                        $("#error1").text("ajax通信でjsonファイルを取得できませんでした");
                    });
            }
        },
        // インスタンスがマウントされた後に呼ばれる
        // DOM要素にアクセスできるようになる
        mounted: function(){
            this.getCommentList();
        }
    })
});
