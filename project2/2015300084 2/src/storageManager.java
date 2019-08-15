


import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.util.*; 

public class storageManager {
	
	
	public static void main(String[] args) throws IOException {
		
		SystemCat systemCatalog = new SystemCat();
		//systemCatalog.deleteType("Human");
		//systemCatalog.createType("cat");
		//systemCatalog.createRecord("Human");
		//systemCatalog.updateRecord("cat");
		//systemCatalog.ListAllTypes();
		//systemCatalog.deleteRecord("Human");
		//systemCatalog.listRecord("cat");
		//systemCatalog.searchRecord("Human");
		String inputFile = args[0];
		String outputFile = args[1];
		System.out.println(inputFile);
		BufferedReader reader = new BufferedReader(new FileReader(inputFile)); 
		BufferedWriter writer = new BufferedWriter(new FileWriter(outputFile));
	    
	   
		String line3;
		String line4;
		String[] arrOfStr;
		while((line3=reader.readLine())!=null){
			System.out.println(line3);
			boolean isEmpty = line3.trim().isEmpty();
			if(isEmpty){
				continue;
			}
			line4=line3.trim().replaceAll("( )+", " ");
			arrOfStr = line4.split(" ");
			
			if((arrOfStr[0]+arrOfStr[1]).equals("createtype")){
				systemCatalog.createType(arrOfStr[2],arrOfStr,writer);
				System.out.println("oldu");
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("deletetype")){
				systemCatalog.deleteType(arrOfStr[2]);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("listtype")){
				systemCatalog.ListAllTypes(writer);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("createrecord")){
				systemCatalog.createRecord(arrOfStr[2],arrOfStr);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("listrecord")){
				systemCatalog.listRecord(arrOfStr[2],writer);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("searchrecord")){
				systemCatalog.searchRecord(arrOfStr[2],arrOfStr[3],writer);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("updaterecord")){
				systemCatalog.updateRecord(arrOfStr[2],arrOfStr[3],arrOfStr);
			}
			else if((arrOfStr[0]+arrOfStr[1]).equals("deleterecord")){
				systemCatalog.deleteRecord(arrOfStr[2],arrOfStr[3]);
			}
			
		} 
		//line=reader.readLine();
		//String line2=reader.readLine();
		//String[] arrOfStr = line.split(" ", 10);
		//int value = Integer.parseInt(arrOfStr[3]);
		//System.out.println(line2);
		
		
		//int value = Integer.parseInt(line);
		//System.out.println(value+1);
		
   
		 writer.close();
		
	} 
	

}

