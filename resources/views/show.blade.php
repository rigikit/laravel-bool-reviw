@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
  <h1 class='pagetitle'>レビュー詳細ページ</h1>
  <div class="card">
    <div class="card-body d-flex">
      <section class='review-main'>
        <h2 class='h2'>本のタイトル</h2>
        <p class='h2 mb20'>{{ $review->title }}</p>
        <h2 class='h2'>レビュー本文</h2>
        <p>{{ $review->body }}</p>
      </section>  
      <aside class='review-image'>
@if(!empty($review->image))
        <img class='book-image' src="{{ asset('storage/images/'.$review->image) }}">
@else
        <img class='book-image' src="{{ asset('images/dummy.png') }}">
@endif
      </aside>
    </div>
    <a href="{{ route('index') }}" class='btn btn-info btn-back mb20'>一覧へ戻る</a>
  </div>
</div>

//ここから追加した
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="app" class="container pt-4">
    <h1 class="mb-3">「いいね！」機能のサンプル</h1>
    <p class="bg-light p-3">「いいね！」できるのは、IPアドレスごとに１回までです。</p>
    <table class="table table-bordered">
        <thead class="bg-info text-white">
            <tr>
                <th>名前</th>
                <th class="text-nowrap">いいね！の回数</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- ユーザーリストを表示 ・・・ ① -->
            <tr v-for="u in users">
                <td class="w-100" v-text="u.name"></td>
                <td class="w-100" v-text="u.likes_count"></td>
                <td class="text-nowrap">
                    <!-- いいね！を実行するボタン ・・・ ② -->
                    <button
                        type="button"
                        class="btn btn-info"
                        :disabled="hasMyLike(u.likes)"
                        @click="addLike(u.id)">いいね！</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script>

    new Vue({
        el: '#app',
        data: {
            users: [],
            ip: '{{ $ip }}'
        },
        methods: {
            addLike(userId) { // いいね！を追加 ・・・ ①

                const url = '/ajax/like';
                const params = { user_id: userId };
                axios.post(url, params)
                    .then(response => {

                        if(response.data.result === true) { // 追加に成功したらデータを更新

                            this.users = response.data.users;

                        }

                    });

            },
            hasMyLike(likes) { // 自分のIPが含まれているかチェック ・・・ ②

                if(likes.length) {

                    for(let like of likes) {

                        if(like.ip === this.ip) {

                            return true;

                        }

                    }

                }

                return false;

            }
        },
        mounted() {

            axios.get('/ajax/like/user_list')
                .then(response => {

                    this.users = response.data;

                });

        }
    });

</script>
</body>
</html>
//ここまで

@endsection