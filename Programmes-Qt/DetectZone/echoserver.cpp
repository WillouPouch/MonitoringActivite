#include "echoserver.h"
#include "QtWebSockets/qwebsocketserver.h"
#include "QtWebSockets/qwebsocket.h"

QT_USE_NAMESPACE


EchoServer::EchoServer(quint16 port, bool debug, QObject *parent) :
    QObject(parent),
    m_pWebSocketServer(new QWebSocketServer(QStringLiteral("Echo Server"),QWebSocketServer::NonSecureMode, this)),
    m_clients(),
    m_debug(debug){

    if (m_pWebSocketServer->listen(QHostAddress::Any, port)) {

        if(m_debug) qDebug() << "[EchoServer::EchoServer] Echoserver listening on port" << port;
        connect(m_pWebSocketServer, &QWebSocketServer::newConnection, this, &EchoServer::onNewConnection);
        connect(m_pWebSocketServer, &QWebSocketServer::closed, this, &EchoServer::closed);
    }

}


EchoServer::~EchoServer() {
    m_pWebSocketServer->close();
    qDeleteAll(m_clients.begin(), m_clients.end());
}


void EchoServer::onNewConnection() {
    QWebSocket *pSocket = m_pWebSocketServer->nextPendingConnection();
    if(m_debug) qDebug() << "[EchoServer::onNewConnection] client login : " << pSocket;
    connect(pSocket, &QWebSocket::disconnected, this, &EchoServer::socketDisconnected);
    m_clients << pSocket;
}


void EchoServer::slot_sendMessage(QString message) {
    if (m_clients.size() > 0) {
        for (int i = 0; i < m_clients.size(); i++) m_clients[i]->sendTextMessage(message);
    }

}

void EchoServer::socketDisconnected() {

    QWebSocket *pClient = qobject_cast<QWebSocket *>(sender());
    if(m_debug) qDebug() << "[EchoServer::socketDisconnected] socketDisconnected : " << pClient;
    if (pClient) {
        m_clients.removeAll(pClient);
        pClient->deleteLater();
    }
}
