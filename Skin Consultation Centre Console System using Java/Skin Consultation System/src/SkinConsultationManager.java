
import java.time.LocalDate;
import java.util.ArrayList;

import Model.Person;

public interface SkinConsultationManager {
    void AddNewDoctor(String firstName, String surName, LocalDate Date_of_Birth, String MobileNumber, String medicalLicenceNumber, String specialization);//Add a New Doctor
    void DeleteDoctor(String MedicalLicenceNumber); //Delete a Doctor
    void PrintDoctorList(); //Display all Doctors and Doctor Details
    void SaveData(); //Save Doctors Data to a File
    ArrayList<Person> LoadSavedData(); //Restore Data from Files

}
