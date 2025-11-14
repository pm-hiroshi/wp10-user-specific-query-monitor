# WP10 User Specific Query Monitor (QM4U)

自分だけにQuery Monitorを有効化するWordPress Must-Useプラグイン

## 概要

Query Monitorは開発に便利なツールですが、本番環境で全員に有効化するとパフォーマンスに影響があります。このプラグインを使用することで、Query Monitorプラグイン自体は有効化したまま、個人設定で自分だけにQuery Monitorを表示できます。

## 機能

- プロフィール画面で個人設定としてQuery Monitorの有効/無効を切り替え可能
- Cookieベースで30日間有効
- ツールバーに「QM4U」という赤背景の表示で、自分だけに有効化されていることを視覚的に確認可能
- Query Monitorの公式無効化機能（`QM_DISABLED`）を使用しているため、安全で確実

## インストール

1. `wp10_user_specific_query_monitor.php` を `wp-content/mu-plugins/` ディレクトリに配置
2. Query Monitorプラグインを有効化
3. プロフィール画面で「Query Monitorを使用する」にチェック

## 使い方

1. WordPress管理画面の「ユーザー」→「プロフィール」にアクセス
2. 「Query Monitor個人設定」セクションで「Query Monitorを使用する」にチェック
3. プロフィールを更新
4. ツールバーに「QM4U」が表示され、Query Monitorが有効化されます

## 技術的な詳細

- Query Monitorの公式無効化機能（`QM_DISABLED` 定数）を使用
- `muplugins_loaded` フックで `QM_DISABLED` を設定することで、初期化処理を完全にスキップ
- ファイルI/Oは1ファイルのみ（`query-monitor.php`の読み込み）
- 初期化処理やクエリの保持は行われないため、メモリ影響はほぼゼロ

## 要件

- WordPress 6.0以上
- PHP 7.4以上
- Query Monitorプラグイン（有効化されている必要があります）

## ライセンス

GPL v2 or later

## 作者

PRESSMAN HS

## バージョン

0.2

