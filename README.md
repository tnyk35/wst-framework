# 1. WSTフレームワークについて
- このフレームワークは、WebStudioTANIが開発したPHPの簡易フレームワークです。
- MITライセンスですので、これベースに作り直すなり、改造するなりご自由にお使いくださいませ。
- ※ただし、本フレームワークを利用することで発生した損失・損害・事故などいかなるトラブル・問題は一切責任を負いません。あらかじめご了承ください。

## 2. URL（ルーティング）のルール
- `/controller/action/paged/page_number/`　の　形となる
- ページングの場合は必ず最後が `/paged/page_number/` になる（例 ユーザーリストの3ページ目を表示 /users/list/paged/3/）
- トップページ（デフォルトのコントローラーとアクション）：`/`
- コントローラーごとのトップページ：`/controller/[ation_name]`
- 通常ページ：`/controller/action/`
- 引数あり：`/controller/action/[引数1/引数2/...]/`