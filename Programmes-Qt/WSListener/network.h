#ifndef NETWORK_H
#define NETWORK_H

#include <QtCore>
#include <QNetworkAccessManager>
#include <QNetworkRequest>
#include <QNetworkReply>

class Network : public QObject {
    Q_OBJECT

    public:
        explicit Network(QString url_site, QObject *parent = Q_NULLPTR,  bool debug = false);
        void post_json(QString path, QJsonDocument &json_doc) const;

    public slots:
        void slot_datareceived(QNetworkReply *);

    private:
        bool m_debug;
        QUrl m_url_site;
        QNetworkAccessManager *m_manager;
    };

#endif // NETWORK_H
