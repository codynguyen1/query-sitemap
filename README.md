# query-sitemap
Query sitemap with rendertron

## Requirement

- PHP above 7.1
- Rendertron installed
- Sitemap created.

## How to run

### 1. Pull the repo

```
git clone https://github.com/codynguyen1/query-sitemap
```

### 2. Go to the folder and edit the config.php

```
cd query-sitemap
vi config.php
```

### 3. Run the scrip
```
process.sh
```
You can run the shell script directly or create a cron to run it

### 4. Check the log at result.log
```
cat result.log
```

### 5. To add to cron
Replace /usr/bin/php7.3 with you php path and Your_query_dir with your path to query-sitemap directory.
```
* * * * * /usr/bin/php7.3 /Your_query_dir/query-sitemap/querySitemap.php > /Your_query_dir/query-sitemap/cronprocess.log
```
