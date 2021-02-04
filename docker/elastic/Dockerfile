FROM elasticsearch:5.6.15

LABEL maintainer="vagif@rufullazada.me"

# Installing russian_morphology plugin
RUN bin/elasticsearch-plugin install \
http://dl.bintray.com/content/imotov/elasticsearch-plugins/org/elasticsearch/elasticsearch-analysis-morphology/5.6.15/elasticsearch-analysis-morphology-5.6.15.zip
