import Interfaces.ViewDoctors;
import Model.Doctor;
import Model.Person;

import java.io.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.Scanner;
import java.util.TreeMap;

public class WestminsterSkinConsultationManager implements SkinConsultationManager {
    public static ArrayList<Person> doctorArray = new ArrayList<>();

    public static void main(String[] args) {
        WestminsterSkinConsultationManager manager = new WestminsterSkinConsultationManager();
        manager.LoadSavedData();
        Scanner enter = new Scanner(System.in);

        while (true) {

            // Print Menu Options
            System.out.println("\n----------*** Skin Consultation System ***----------");
            System.out.println("\n----------------------- Menu -----------------------");
            System.out.println("\nEnter 1 to Add a New Doctor");
            System.out.println("\nEnter 2 to Delete a Doctor");
            System.out.println("\nEnter 3 to Display Doctors List");
            System.out.println("\nEnter 4 to Save the Details");
            System.out.println("\nEnter 5 to Display Saved Data");
            System.out.println("\nEnter 6 to Open Interface.GUI");
            System.out.println("\nEnter 0 to Exit from the Program");
            System.out.println("\n----------------------------------------------------");
            System.out.print("\nPlease Enter Your Command: ");

            String input = enter.nextLine();

            if ("1".equalsIgnoreCase(input)) {
                if (doctorArray.size() < 10) {
                    System.out.print("\nEnter First Name: ");
                    String Firstname = enter.nextLine();

                    System.out.print("\nEnter Sur Name: ");
                    String Surname = enter.nextLine();

                    LocalDate Date_of_Birth;
                    while (true) {
                        System.out.print("\nEnter Date of Birth(YYYY-MM-DD): ");
                        String Birthday = enter.nextLine();
                        try {
                            Date_of_Birth = LocalDate.parse(Birthday);
                            break;
                        } catch (Exception ex) {
                            System.out.println("Invalid date format..!");
                        }
                    }
                    String MobileNumber;
                    while (true) {
                        System.out.print("\nEnter Mobile Number: ");
                        MobileNumber = enter.nextLine();
                        try {
                            if (MobileNumber.length() == 10) {
                                Integer.parseInt(MobileNumber);
                                break;
                            } else {
                                System.out.println("Invalid Number!");
                            }
                        } catch (Exception ex) {
                            System.out.println("Invalid Number!");
                        }
                    }
                    System.out.print("\nEnter Medical Licence Number: ");
                    String medicalNumber = enter.nextLine();

                    System.out.print("\nEnter Specialization: ");
                    String specialization = enter.nextLine();

                    manager.AddNewDoctor(Firstname, Surname, Date_of_Birth, MobileNumber, medicalNumber,
                            specialization);
                } else {
                    System.out.println("Clinic is full!");
                }
            } else if ("2".equalsIgnoreCase(input)) {
                System.out.print("\nEnter Medical Licence Number: ");
                String medicalNumber = enter.nextLine();
                manager.DeleteDoctor(medicalNumber);

            } else if ("3".equalsIgnoreCase(input)) {
                System.out.println("\nDoctors List");
                manager.PrintDoctorList();

            } else if ("4".equalsIgnoreCase(input)) {
                try {
                    manager.SaveData();
                } catch (Exception e) {
                    System.out.println(e);
                }
                System.out.println("\nDetails are Saved!");

            } else if ("5".equalsIgnoreCase(input)) {
                try {
                    manager.LoadSavedData();
                    System.out.println("Data loaded");
                } catch (Exception e) {
                    System.out.println(e);
                }
            } else if ("6".equalsIgnoreCase(input)) {
                String name = "Deshani";
                String surname = "Liyanage";
                String phone = "0912275285";
                String licence = "ML00115";
                LocalDate dob = LocalDate.parse("1987-07-19");
                String spec = "Cardiologist";

                manager.AddNewDoctor(name, surname, dob, licence, phone, spec);

                
                String name1 = "Anusree";
                String surname1 = "Sadasivan";
                String phone1 = "0764434765";
                String licence1 = "ML00116";
                LocalDate dob1 = LocalDate.parse("1987-07-19");
                String spec1 = "Neurologist";
                
                manager.AddNewDoctor(name1, surname1, dob1, licence1, phone1, spec1);

                ViewDoctors viewDoctors = new ViewDoctors(doctorArray);
                viewDoctors.setVisible(true);

            } else if ("0".equalsIgnoreCase(input)) {
                System.out.println("\nSaving and Exiting.!");
                break;
            } else {
                System.out.println("Invalid Input");
            }

        }
        enter.close();
    }

    @Override
    public void AddNewDoctor(String firstName, String surName, LocalDate Date_of_Birth, String MobileNumber,
            String medicalLicenceNumber, String specialization) {
        Person doctor = new Doctor(firstName, surName, Date_of_Birth, MobileNumber, medicalLicenceNumber,
                specialization, new TreeMap<>());
        doctorArray.add(doctor);
        System.out.println("/nNew Doctor added -> " + doctorArray.get(doctorArray.size() - 1));
    }


    @Override
    public void DeleteDoctor(String MedicalLicenceNumber) {
        for (int i = 0; i < doctorArray.size(); i++) {
            if (((Doctor) doctorArray.get(i)).getMedicalLicenceNumber().equalsIgnoreCase(MedicalLicenceNumber)) {
                System.out.println("Doctor removed ->" + doctorArray.get(i));
                doctorArray.remove(i);
                return;
            }
        }
        System.out.println("No Doctor from that Number");

    }

    @Override
    public void PrintDoctorList() {
        WestminsterSkinConsultationManager.doctorArray.sort(new Comparator<Person>() {
            @Override
            public int compare(Person o1, Person o2) {
                return o1.getSurname().compareTo(o2.getSurname());
            }
        });
        // Print the information for each doctor
        for (Person doctor : doctorArray) {
            System.out.println("Doctor Name: " + doctor.getFirstName() + " " + doctor.getSurname());
            System.out.println("Medical Licence Number: " + doctor.getMedicalLicenceNumber());
            System.out.println("Specialization: " + doctor.getSpecialisation());
            System.out.println();

        }
    }

    @Override
    public void SaveData() {
        try {
            File file = new File("doctorList.txt");
            FileOutputStream fileOutputStream = new FileOutputStream(file);
            try (ObjectOutputStream objectOutputStream = new ObjectOutputStream(fileOutputStream)) {
                objectOutputStream.writeObject(doctorArray);
            }
        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
    }

    @Override
    public ArrayList<Person> LoadSavedData() {
        ArrayList<Person> doctorsList = new ArrayList<>();
        try {
            File file = new File("doctorList.txt");
            FileInputStream fileInputStream = new FileInputStream(file);
            ObjectInputStream objectInputStream = new ObjectInputStream(fileInputStream);

            Person obj = null;
            while ((obj = (Person) objectInputStream.readObject()) != null) {
                doctorsList.add(obj);
            }

            objectInputStream.close();

        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
        return doctorsList;
    }

}
