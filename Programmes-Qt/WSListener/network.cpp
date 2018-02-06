#include "network.h"

Network::Network(QString url_site, QObject *parent, bool debug) :
    QObject(parent),
    m_url_site(url_site),
    m_debug(debug){

    m_manager = new QNetworkAccessManager(this);
    connect(m_manager, &QNetworkAccessManager::finished,this, &Network::slot_datareceived);

}

void Network::post_json(QString path, QJsonDocument &json) const{

    if(m_debug) qDebug() << "json  :  " << json.toJson(QJsonDocument::Compact);

    QNetworkRequest request(QUrl(m_url_site.toString()+path));
    request.setHeader(QNetworkRequest::ContentTypeHeader, "application/x-www-form-urlencoded");
    QByteArray postData;
    postData.append("json="+json.toJson(QJsonDocument::Compact));
    m_manager->post(request, postData);
}


void Network::slot_datareceived(QNetworkReply * networkReply){

    QJsonDocument data = QJsonDocument::fromJson(QString(networkReply->readAll()).toUtf8());
    QJsonObject state = data.object();

    if(m_debug){
        qDebug() << "======= Network Reply =======";
        qDebug() << " STATE : " << state["STATE"].toString();
        if(state["STATE"]=="KO") qDebug() << " ERROR : " << state["ERROR"].toString();
        qDebug() << "=============================";
    }
}
