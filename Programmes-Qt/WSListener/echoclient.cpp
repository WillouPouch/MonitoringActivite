#include "echoclient.h"

QT_USE_NAMESPACE

EchoClient::EchoClient(const QUrl &url, QObject *parent, bool debug) :
    QObject(parent),
    m_url(url),
    m_debug(debug){

    if (m_debug) qDebug() << "[EchoClient::EchoClient] WebSocket client : " << url.toString();
    //connect(&m_webSocket, &QWebSocket::connected, this, &EchoClient::onConnected);
    //connect(&m_webSocket, &QWebSocket::disconnected, this, &EchoClient::closed);

    m_webSocket = new QWebSocket;
    connect(m_webSocket, static_cast<void(QWebSocket::*)(QAbstractSocket::SocketError)>(&QWebSocket::error),
    [=](QAbstractSocket::SocketError error){

        QTimer::singleShot(3000, this, SLOT(reconnect()));
        if (m_debug) qDebug() << "[EchoClient::EchoClient] Erreur : " << m_webSocket->errorString() << " -- " << error;
    });

    connect(m_webSocket, &QWebSocket::textMessageReceived, this, &EchoClient::onTextMessageReceived);
    m_webSocket->open(m_url);

}

void EchoClient::reconnect(){
    m_webSocket->open(m_url);
}

void EchoClient::onTextMessageReceived(QString message){
    if(m_debug) qDebug() << "[EchoClient::onTextMessageReceived] Message received : " << message;
    emit signal_datareceived(message);
}
