#include "controller.h"

Controller::Controller(QObject *parent, bool debug) :
    QObject(parent)
    ,m_zone("")
    ,m_start_date(QDateTime::currentDateTime())
    ,m_debug(debug){

}

//Lorsque le patient change de zone dans la pièce
void Controller::zone_changed(QString zone){

    if( m_zone.isEmpty() ){
        m_zone = zone;
        m_start_date = QDateTime::currentDateTime();
        return;
    }

    if( m_zone != zone){

        if(m_debug) qDebug() << "[Controller::zone_changed] nouvelle zone : " << zone;
        QDateTime end_date = QDateTime::currentDateTime();

        QJsonObject obj;
        obj.insert("date_debut", m_start_date.toString(QString("dd/MM/yyyy HH:mm:ss")));
        obj.insert("date_fin", end_date.toString(QString("dd/MM/yyyy HH:mm:ss")));
        obj.insert("type_activite", m_zone);
        QJsonDocument doc(obj);
        if(m_debug) qDebug() << "[Controller::zone_changed] doc : " << doc.toJson(QJsonDocument::Compact);
        emit send_data(doc.toJson(QJsonDocument::Compact));

        m_zone = zone;
        m_start_date = end_date;
    }
}

