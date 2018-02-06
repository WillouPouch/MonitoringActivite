#ifndef LISTEANOMALIESONORE_H
#define LISTEANOMALIESONORE_H

#include <QVector>
#include <QJsonDocument>
#include <QJsonArray>
#include <QJsonObject>

#include "anomaliesonore.h"
#include "network.h"

class ListeAnomalieSonore: public QObject {
    Q_OBJECT

public:
    explicit ListeAnomalieSonore(unsigned int nb_record, bool debug = false, QObject *parent = Q_NULLPTR);
    void append_custom(AnomalieSonore & enc);
    void prepare_json();

private:
     bool m_debug;
     unsigned int m_nb_record;
     QVector<AnomalieSonore> m_vector;

signals:
    void as_json_ready(QJsonDocument);

};


#endif // LISTEANOMALIESONORE_H
