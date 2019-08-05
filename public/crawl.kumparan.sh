#!bin/bash
category_lists=$(cat kumparan_categories_list.txt)

for i in $category_lists
do
	date=$(date +"%Y%m%d.%T")
	wget -O --spider --force-html -r -l1 "https://kumparan.com/${i}" 2>log.crawl.${i}.with.l1.${date}
	cat log.crawl.${i}.with.l1.${date} | grep https | cut -c26- | sort -u | sed '/response/d' | sed '/channel/d' | sed '/favicon/d' | sed '/logo-192/d' | sed '/manifest.json/d' | sed '/promote/d' | sed '/robots/d' | sed '/galeri-foto/d' | sed '/kumparan-video/d' | sed '/trending/d' | sed "/${i}$/d" | sed '/kumparan.com\/$/d' > log.crawl.${i}.with.l1.filtered.${date}

done
