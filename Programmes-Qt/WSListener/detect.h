#ifndef DETECT_H
#define DETECT_H

#include <QtCore>
#include "activite.h"
#include "anomaliesonore.h"
#include "listeactivite.h"
#include "listeanomaliesonore.h"
#include "network.h"

class Detect : public QObject {
    Q_OBJECT

public:
    explicit Detect(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    bool m_debug;
    ListeActivite m_liste_act;
    ListeAnomalieSonore m_liste_as;
    Network m_network;

public slots:
    void slot_datareceived(QString);
    void send_liste_act_json(QJsonDocument);
    void send_liste_as_json(QJsonDocument);

};

#endif // DETECT_H
