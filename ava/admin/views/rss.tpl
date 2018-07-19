<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0">
<channel>
<title>{RSS_TITLE}</title>
<link>{LINK}</link>
<copyright>{COPYRIGHT}</copyright>
 <language>{LANG}</language>
{row:ITEM}
 <item>
 <title>{TITLE}</title>
<link>{LINK}{ID}/</link>
<description>{DESCR}</description>
<pubDate>{DATA}</pubDate>
</item>
{/row}
</channel>
</rss>