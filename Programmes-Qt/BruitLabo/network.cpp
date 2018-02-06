#include "network.h"

Network::Network(QString url_site, QObject *parent, bool debug) :
    QObject(parent),
    m_url_site(url_site),
    m_debug(debug){

    m_manager = new QNetworkAccessManager(this);
    connect(m_manager, &QNetworkAccessManager::finished,this, &Network::slot_datareceived);

}

void Network::get_json(QString path) const{

    QNetworkRequest request(QUrl(m_url_site.toString()+path));
    m_manager->get(request);
}


void Network::slot_datareceived(QNetworkReply * networkReply){
    emit data_received(QString(networkReply->readAll()).toUtf8());
}
